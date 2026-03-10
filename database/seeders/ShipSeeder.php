<?php

namespace Database\Seeders;

use App\Models\Ship;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ships = [
            ['name' => 'OPS AQEELA', 'type' => 'AHTS', 'image' => 'aqeela.png'],
            ['name' => 'OPS AVIOR', 'type' => 'AHTS', 'image' => 'avior.png'],
            ['name' => 'OPS ANDRA', 'type' => 'AHTS', 'image' => 'andra.png'],
            ['name' => 'OPS AORA', 'type' => 'AHTS', 'image' => 'aora.png'],
            ['name' => 'OPS ALHENA', 'type' => 'AHT', 'image' => 'alhena.png'],
            ['name' => 'OPS ALNAIR', 'type' => 'AHT', 'image' => 'alnair.png'],
            ['name' => 'OPS ASTRID', 'type' => 'AHT', 'image' => 'astrid.png'],
            ['name' => 'OPS ALHEMI', 'type' => 'AHT', 'image' => 'alhemi.png'],
        ];

        foreach ($ships as $ship) {
            Ship::create($ship);
        }
    }
}
