<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ship;
use App\Models\Machinery;
use App\Models\MaintenanceTask;
use App\Models\MaintenanceHistory;
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
}
