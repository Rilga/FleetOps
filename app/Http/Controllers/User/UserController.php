<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machinery;
use App\Models\MaintenanceTask;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userName = $user->name; // Mengambil nama: "2nd Engineer" atau "3rd Engineer"

        // Logika Penentuan PIC berdasarkan Nama
        if (str_contains($userName, '2nd')) {
            $picTarget = '2E';
            $responsibility = 'Main Engine & Reduction Gear';
        } elseif (str_contains($userName, '3rd')) {
            $picTarget = '3E';
            $responsibility = 'Generators & Pumps';
        } else {
            // Jika nama tidak mengandung 2nd atau 3rd (misal Chief/Admin)
            return redirect()->route('dashboard'); 
        }

        // Ambil data mesin yang memiliki tugas dengan PIC tersebut
        $machineries = Machinery::whereHas('maintenanceTasks', function($q) use ($picTarget) {
            $q->where('pic', $picTarget);
        })->get();

        // Ambil daftar tugas yang mendesak (Warning/Critical)
        $urgentTasks = MaintenanceTask::where('pic', $picTarget)
            ->whereIn('status', ['warning', 'critical'])
            ->with('machinery')
            ->get();

        return view('user.dashboard', compact('machineries', 'urgentTasks', 'responsibility', 'picTarget'));
    }
}
