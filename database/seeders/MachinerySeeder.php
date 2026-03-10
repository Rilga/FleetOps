<?php

namespace Database\Seeders;

use App\Models\Ship;
use App\Models\Machinery;
use Illuminate\Database\Seeder;

class MachinerySeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua kapal dari database (8 kapal: Aqeela, Avior, Andra, dll)
        $allShips = Ship::all();

        foreach ($allShips as $ship) {
            
            // Kita buat senarai mesin yang WAJIB ada di setiap kapal
            $defaultMachineries = [
                [
                    'name' => 'Port Main Engine (ME-P)',
                    'maker' => 'Caterpillar',
                    'model' => '3516 B-HD',
                    'serial_number' => ($ship->name == 'OPS AQEELA') ? 'S2X01163' : 'TBA', 
                    'power' => '1920-KW'
                ],
                [
                    'name' => 'Starboard Main Engine (ME-S)',
                    'maker' => 'Caterpillar',
                    'model' => '3516 B-HD',
                    'serial_number' => ($ship->name == 'OPS AQEELA') ? 'S2X01157' : 'TBA',
                    'power' => '1920-KW'
                ],
                [
                    'name' => 'Main Generator No. 1 (AE-1)',
                    'maker' => 'Volvo Penta',
                    'model' => 'D30A MS D',
                    'serial_number' => ($ship->name == 'OPS AQEELA') ? '54030072426' : 'TBA',
                    'power' => 'DEFAULT-KW'
                ],
                [
                    'name' => 'Main Generator No. 2 (AE-2)',
                    'maker' => 'Volvo Penta',
                    'model' => 'D30A MS D',
                    'serial_number' => ($ship->name == 'OPS AQEELA') ? '54030072520' : 'TBA',
                    'power' => 'DEFAULT-KW'
                ],
                [
                    'name' => 'Main Generator No. 3 (AE-3)',
                    'maker' => 'Volvo Penta',
                    'model' => 'D30A MS D',
                    'serial_number' => ($ship->name == 'OPS AQEELA') ? '54030072427' : 'TBA',
                    'power' => 'DEFAULT-KW'
                ],
            ];

            foreach ($defaultMachineries as $m) {
                Machinery::create([
                    'ship_id'       => $ship->id, // Automatik masuk ke id kapal (Aqeela, Avior, dll)
                    'name'          => $m['name'],
                    'maker'         => $m['maker'],
                    'model'         => $m['model'],
                    'serial_number' => $m['serial_number'],
                    'power'         => $m['power'],
                    'current_rh'    => 0, // Semua bermula dari 0
                ]);
            }
        }
    }
}