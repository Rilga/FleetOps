<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ship;
use App\Models\Machinery;
use App\Models\MaintenanceTask;
use App\Models\MaintenanceHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        // 1. Ambil semua kapal dengan relasi lengkap
        $ships = Ship::with(['machineries.maintenanceTasks'])->get();

        // --- LOGIKA DATA GRAFIK (Real Data) ---
        $months = [];
        $complianceData = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $months[] = $monthDate->format('M');

            // Hitung total tugas pada bulan tersebut
            // Kita hitung history yang terverifikasi pada bulan itu
            $verifiedCount = MaintenanceHistory::where('is_verified', true)
                ->whereMonth('completion_date', $monthDate->month)
                ->whereYear('completion_date', $monthDate->year)
                ->count();

            // Hitung total task yang overdue pada bulan tersebut (sebagai pembanding)
            $totalTaskCount = MaintenanceTask::count();
            
            // Kalkulasi sederhana: (Verified / Total Task) * 100
            // Anda bisa menyesuaikan rumusnya sesuai KPI perusahaan
            $rate = $totalTaskCount > 0 ? round(($verifiedCount / $totalTaskCount) * 100) : 0;
            $complianceData[] = $rate > 100 ? 100 : $rate;
        }

        // 2. Statistik Global untuk Counter di Dashboard
        $fleetStats = [
            'total_ships' => $ships->count(),
            'total_machineries' => Machinery::count(),
            'overdue_tasks' => MaintenanceTask::where('status', 'critical')->count(),
            'will_due_tasks' => MaintenanceTask::where('status', 'warning')->count(),
            'pending_approvals' => MaintenanceHistory::where('is_verified', false)->count(),
        ];

        // 3. Data pengerjaan terselesaikan dalam 30 hari terakhir (Performance Tracking)
        $completedThisMonth = MaintenanceHistory::where('is_verified', true)
                                ->where('created_at', '>=', now()->subDays(30))
                                ->count();

        return view('admin.dashboard', compact('ships', 'fleetStats', 'completedThisMonth', 'months', 'complianceData'));
    }

    public function analyzeVessel($ship_id)
    {
        $ship = Ship::with(['machineries.maintenanceTasks.histories'])->findOrFail($ship_id);
        
        // Hitung persentase kepatuhan (Task selesai vs total task)
        $totalTasks = $ship->machineries->flatMap->maintenanceTasks->count();
        $overdueTasks = $ship->machineries->flatMap->maintenanceTasks->where('status', 'critical')->count();
        $complianceRate = $totalTasks > 0 ? round((($totalTasks - $overdueTasks) / $totalTasks) * 100) : 100;

        return view('admin.vessel_analysis', compact('ship', 'complianceRate', 'overdueTasks'));
    }

    public function vesselAuditLog($ship_id)
    {
        // Cari kapal atau gagalkan jika tidak ada
        $ship = Ship::findOrFail($ship_id);
        
        // Ambil riwayat dari semua mesin di kapal ini
        $auditLogs = MaintenanceHistory::whereHas('task.machinery', function($query) use ($ship_id) {
            $query->where('ship_id', $ship_id);
        })
        ->with(['task.machinery', 'verifier'])
        ->latest()
        ->paginate(20);

        return view('admin.vessel_audit_log', compact('ship', 'auditLogs'));
    }

    public function exportShipPDF(Request $request, $id)
    {
        $validated = $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        if (filled($validated['start_date'] ?? null) xor filled($validated['end_date'] ?? null)) {
            return back()->withErrors('Tanggal mulai dan tanggal selesai harus diisi bersamaan.');
        }

        $historyFilter = function ($query) use ($validated) {
            if (filled($validated['start_date'] ?? null)) {
                $query->whereBetween('completion_date', [
                    $validated['start_date'],
                    $validated['end_date'],
                ]);

                return;
            }

            if (filled($validated['month'] ?? null)) {
                $query->whereYear('completion_date', substr($validated['month'], 0, 4))
                    ->whereMonth('completion_date', substr($validated['month'], 5, 2));
            }
        };

        // Hanya ambil task yang memiliki riwayat maintenance pada periode yang dipilih.
        $ship = Ship::with([
            'machineries.maintenanceTasks' => function ($query) use ($historyFilter) {
                $query->whereHas('histories', $historyFilter)
                    ->with(['histories' => $historyFilter]);
            },
        ])->findOrFail($id);

        $period = 'All maintenance history';
        if (filled($validated['start_date'] ?? null)) {
            $period = 'Completion date: ' . $validated['start_date'] . ' to ' . $validated['end_date'];
        } elseif (filled($validated['month'] ?? null)) {
            $period = 'Completion month: ' . \Carbon\Carbon::createFromFormat('Y-m', $validated['month'])->format('F Y');
        }
        
        $data = [
            'ship' => $ship,
            'date' => now()->format('d F Y H:i'),
            'title' => 'Fleet Technical Report - ' . $ship->name,
            'period' => $period,
        ];

        // Load view khusus untuk PDF dan set ke Landscape
        $pdf = Pdf::loadView('admin.reports.ship_pdf', $data)
                  ->setPaper('a4', 'landscape');

        // Download file dengan nama yang bersih
        $fileName = 'Technical_Report_' . str_replace(' ', '_', $ship->name) . '_' . date('Ymd') . '.pdf';
        
        return $pdf->download($fileName);
    }
}
