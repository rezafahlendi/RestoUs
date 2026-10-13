<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            ['table_number' => 'Meja 01', 'capacity' => 2, 'status' => 'available'],
            ['table_number' => 'Meja 02', 'capacity' => 2, 'status' => 'available'],
            ['table_number' => 'Meja 03', 'capacity' => 4, 'status' => 'available'],
            ['table_number' => 'Meja 04', 'capacity' => 4, 'status' => 'available'],
            ['table_number' => 'Meja 05', 'capacity' => 6, 'status' => 'available'],
            ['table_number' => 'Meja VIP', 'capacity' => 10, 'status' => 'available'],
        ];

        foreach ($tables as $table) {
            Table::firstOrCreate(['table_number' => $table['table_number']], $table);
        }
    }
}
