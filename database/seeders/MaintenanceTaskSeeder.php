<?php

namespace Database\Seeders;

use App\Models\Machinery;
use App\Models\MaintenanceTask;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MaintenanceTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil SEMUA mesin yang ada di database dari SEMUA kapal
        $machineries = Machinery::all();

        foreach ($machineries as $m) {
            $tasks = [];
            $machineName = Str::lower($m->name);

            // 1. Logika Tugas untuk Main Engine (PIC: 2E)
            if (Str::contains($machineName, 'main engine')) {
                $tasks = [
                    ['system' => 'Cooling', 'job_details' => 'Clean SW Strainer', 'interval' => 250, 'pic' => '2E'],
                    ['system' => 'Lube Oil', 'job_details' => 'Sample Lubricating Oil', 'interval' => 250, 'pic' => '2E'],
                    ['system' => 'Electrical', 'job_details' => 'Inspect Alternator Belt', 'interval' => 500, 'pic' => '2E'],
                    ['system' => 'General', 'job_details' => 'Inspect Hoses and Clamps', 'interval' => 1000, 'pic' => '2E'],
                ];
            } 
            // 2. Logika Tugas untuk Generator / Auxiliary Engine (PIC: 3E)
            elseif (Str::contains($machineName, 'generator') || Str::contains($machineName, 'auxiliary')) {
                $tasks = [
                    ['system' => 'Lube Oil', 'job_details' => 'Change Oil and Replace Filter', 'interval' => 250, 'pic' => '3E'],
                    ['system' => 'Air System', 'job_details' => 'Clean Air Pre-Cleaner (Turbo)', 'interval' => 250, 'pic' => '3E'],
                    ['system' => 'Control', 'job_details' => 'Check Stop Solenoid', 'interval' => 500, 'pic' => '3E'],
                    ['system' => 'Fuel System', 'job_details' => 'Clean Fuel Primary Filter', 'interval' => 500, 'pic' => '3E'],
                ];
            }
            // 3. Tugas Umum (Jika mesin tidak mengandung nama di atas, misal: Pump, Compressor)
            else {
                $tasks = [
                    ['system' => 'Maintenance', 'job_details' => 'General Inspection & Greasing', 'interval' => 500, 'pic' => '3E'],
                    ['system' => 'Electrical', 'job_details' => 'Check Motor Connection', 'interval' => 1000, 'pic' => '2E'],
                ];
            }

            // Simpan ke database
            foreach ($tasks as $task) {
                MaintenanceTask::create([
                    'machinery_id' => $m->id,
                    'system'       => $task['system'],
                    'job_details'  => $task['job_details'],
                    'interval'     => $task['interval'],
                    'pic'          => $task['pic'],
                    'last_done_rh' => 0,
                    'next_due_rh'  => $m->current_rh + $task['interval'], // Dinamis berdasarkan RH mesin saat ini
                    'status'       => 'normal',
                ]);
            }
        }
    }
}