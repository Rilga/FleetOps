<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ship;
use App\Models\Machinery;
use App\Models\MaintenanceTask;
use App\Models\MachineryDailyLog;
use App\Models\MaintenanceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FleetController extends Controller
{
    public function index()
    {
        $ships = Ship::all(); // Menampilkan ke-8 kapal
        return view('user.fleet', compact('ships'));
    }

    public function machineries($ship_id)
    {
        $ship = Ship::findOrFail($ship_id);
        
        // Debug 1: Cek apakah mesin muncul tanpa filter Task
        // $machineries = Machinery::where('ship_id', $ship_id)->get();

        // Debug 2: Jika ingin tetap pakai filter, pastikan PIC sesuai
        $userName = Auth::user()->name;
        $picTarget = str_contains($userName, '2nd') ? '2E' : '3E';

        $machineries = Machinery::where('ship_id', $ship_id)
            ->whereHas('maintenanceTasks', function($q) use ($picTarget) {
                $q->where('pic', 'LIKE', '%' . $picTarget . '%'); // Gunakan LIKE agar lebih fleksibel
            })->get();

        return view('user.machineries', compact('ship', 'machineries'));
    }





    /**
     * Menampilkan Form Input RH
     */
    public function inputRh($machinery_id)
    {
        $machinery = Machinery::findOrFail($machinery_id);
        return view('user.input.input_rh', compact('machinery'));
    }

    /**
     * Logika Menyimpan RH dan Update Status Warning Otomatis
     */
    public function storeRh(Request $request, $machinery_id)
    {
        $request->validate([
            'current_reading' => 'required|numeric|min:0',
            'log_date' => 'required|date',
        ]);

        $machinery = Machinery::findOrFail($machinery_id);
        $prev_rh = $machinery->current_rh;
        $new_rh = $request->current_reading;
        $isBreakdown = $request->has('is_breakdown') ? true : false;

        // 1. Simpan Riwayat ke MachineryDailyLog
        MachineryDailyLog::create([
            'machinery_id' => $machinery_id,
            'log_date' => $request->log_date,
            'previous_reading' => $prev_rh,
            'current_reading' => $new_rh,
            'is_breakdown' => $isBreakdown,
            'remarks' => $request->remarks,
            'user_id' => Auth::id()
        ]);

        // 2. Update jam kerja utama mesin
        $machinery->update(['current_rh' => $new_rh]);

        // 3. Update Status Semua Task Terkait
        $tasks = MaintenanceTask::where('machinery_id', $machinery_id)->get();

        foreach ($tasks as $task) {
            $remaining = $task->next_due_rh - $new_rh;

            // NOVELTY LOGIC: Penentuan Status Warna
            if ($isBreakdown || $remaining <= 0) {
                $status = 'critical'; // Merah: Rusak atau Overdue
            } elseif ($remaining <= 50) {
                $status = 'warning';  // Kuning: Sisa jam < 50
            } else {
                $status = 'normal';   // Hijau
            }

            $task->update(['status' => $status]);
        }

        return redirect()->route('user.machineries', $machinery->ship_id)
                         ->with('success', 'Daily log updated. Status: ' . ($isBreakdown ? 'BREAKDOWN' : 'NORMAL'));
    }

    /**
     * Menampilkan Daftar Job Maintenance
     */
    public function jobList($machinery_id)
    {
        $machinery = Machinery::with('maintenanceTasks')->findOrFail($machinery_id);
        return view('user.input.job_list', compact('machinery'));
    }

    /**
     * Logika Selesai Pengerjaan (Reset Interval)
     */
    public function completeTask(Request $request, $task_id)
    {
        // 1. Validasi input remarks agar tidak kosong
        $request->validate([
            'remarks' => 'required|string|max:500',
        ]);

        $task = MaintenanceTask::findOrFail($task_id);
        $machinery = Machinery::findOrFail($task->machinery_id);
        $current_rh = $machinery->current_rh;

        // 2. Simpan ke tabel riwayat (MaintenanceHistory)
        // Ini akan menambah baris baru terus menerus (Check ke-6, 7, dst tetap tersimpan)
        \App\Models\MaintenanceHistory::create([
            'maintenance_task_id' => $task->id,
            'done_at_rh'          => $current_rh,
            'completion_date'     => now(),
            'remarks'             => $request->remarks,
        ]);

        // 3. Update data pada tabel utama MaintenanceTask
        // Kita tetap mengupdate tabel ini agar Warning System (Kuning/Merah) tetap akurat
        $task->update([
            'last_done_rh' => $current_rh,
            'next_due_rh'  => $current_rh + $task->interval,
            'status'       => 'normal', // Reset ke hijau setelah dikerjakan
            'remarks'      => $request->remarks // Menyimpan catatan servis terakhir
        ]);

        return back()->with('success', 'Maintenance record saved to history. Next schedule: ' . ($current_rh + $task->interval) . ' HRS');
    }

    public function alerts(Request $request)
    {
        $userName = Auth::user()->name;
        $picTarget = str_contains($userName, '2nd') ? '2E' : '3E';
        
        // Ambil filter dari URL, default-nya 'all'
        $filter = $request->query('filter', 'all');

        $query = MaintenanceTask::with('machinery.ship')
            ->where('pic', $picTarget);

        // Tentukan kondisi berdasarkan filter
        if ($filter == 'critical') {
            $query->where('status', 'critical');
        } elseif ($filter == 'warning') {
            $query->where('status', 'warning');
        } else {
            // Jika 'all', ambil keduanya
            $query->whereIn('status', ['warning', 'critical']);
        }

        $urgentTasks = $query->orderByRaw("FIELD(status, 'critical', 'warning')")->get();

        return view('user.alerts', compact('urgentTasks', 'picTarget', 'filter'));
    }
    
    public function maintenanceHistory($machinery_id)
    {
        // Mengambil data mesin dan riwayat pengerjaannya
        $machinery = Machinery::with(['ship'])->findOrFail($machinery_id);
        
        // Mengambil riwayat pengerjaan yang sudah dilakukan pada mesin ini
        $histories = MaintenanceHistory::whereHas('task', function($query) use ($machinery_id) {
            $query->where('machinery_id', $machinery_id);
        })
        ->with(['task', 'verifier'])
        ->latest()
        ->paginate(15);

        return view('user.maintenance_history', compact('machinery', 'histories'));
    } 
}