<?php

namespace App\Http\Controllers\Chief;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ship;
use App\Models\Machinery;
use App\Models\MaintenanceTask;
use App\Models\MaintenanceHistory;
use App\Models\MachineryDailyLog;
use Illuminate\Support\Facades\Auth;

class ChiefController extends Controller
{
    /**
     * Dashboard Utama Chief Engineer
     */
    public function index() 
    {
        // 1. Chief memantau SEMUA kapal dengan eager loading relasi
        $ships = Ship::with(['machineries.maintenanceTasks'])->get();

        // 2. Mengambil aktivitas terbaru dari SELURUH armada (15 data terakhir)
        $recentActivities = MaintenanceHistory::with('task.machinery.ship')
                            ->latest()
                            ->take(15)
                            ->get();

        // 3. Ambil HANYA 2 data yang belum diverifikasi untuk tampilan Dashboard
        $pendingApprovals = MaintenanceHistory::with(['task.machinery.ship'])
                            ->where('is_verified', false)
                            ->latest()
                            ->take(2) 
                            ->get();

        // 4. Hitung TOTAL semua yang belum diverifikasi (untuk label di tombol More)
        $totalPending = MaintenanceHistory::where('is_verified', false)->count();

        // 5. Menghitung total statistik fleet (Critical & Warning)
        $totalCritical = 0;
        $totalWarning = 0;
        
        foreach($ships as $ship) {
            $tasks = $ship->machineries->flatMap->maintenanceTasks;
            $totalCritical += $tasks->where('status', 'critical')->count();
            $totalWarning += $tasks->where('status', 'warning')->count();
        }

        return view('chief.dashboard', compact(
            'ships', 
            'recentActivities', 
            'totalCritical', 
            'totalWarning', 
            'pendingApprovals', 
            'totalPending'
        ));
    }

    /**
     * Halaman Daftar Lengkap Persetujuan (Approval Queue)
     */
    public function approvalList()
    {
        // Mengambil semua yang belum diverifikasi dengan pagination
        $pendingApprovals = MaintenanceHistory::with(['task.machinery.ship'])
                            ->where('is_verified', false)
                            ->latest()
                            ->paginate(15); 

        return view('chief.approvals', compact('pendingApprovals'));
    }

    /**
     * Proses Verifikasi Tugas oleh Chief
     */
    public function verifyTask($history_id)
    {
        $history = MaintenanceHistory::findOrFail($history_id);

        $history->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        // Menggunakan redirect back agar fleksibel saat diklik dari dashboard maupun list
        return back()->with('success', 'Job successfully verified and recorded in vessel log.');
    }

    public function inspectVessel($ship_id)
    {
        // Mengambil data kapal beserta seluruh mesin dan task-nya
        $ship = Ship::with(['machineries.maintenanceTasks'])->findOrFail($ship_id);
        
        // Menghitung statistik cepat untuk kapal ini
        $stats = [
            'critical' => $ship->machineries->flatMap->maintenanceTasks->where('status', 'critical')->count(),
            'warning'  => $ship->machineries->flatMap->maintenanceTasks->where('status', 'warning')->count(),
        ];

        return view('chief.vessel_inspect', compact('ship', 'stats'));
    }

    public function machineryHistory($machinery_id)
    {
        // Mengambil data mesin beserta semua task dan riwayat pengerjaannya
        $machinery = Machinery::with([
            'ship', 
            'maintenanceTasks.histories' => function($query) {
                $query->with('verifier')->latest(); // Urutkan dari yang terbaru
            }
        ])->findOrFail($machinery_id);

        // Ambil semua history secara flat agar bisa ditampilkan dalam satu tabel kronologis
        $allHistories = MaintenanceHistory::whereIn('maintenance_task_id', $machinery->maintenanceTasks->pluck('id'))
                        ->with(['task', 'verifier'])
                        ->latest()
                        ->paginate(20);

        return view('chief.machinery_history', compact('machinery', 'allHistories'));
    }
}