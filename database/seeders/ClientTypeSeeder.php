<?php

namespace Database\Seeders;

use App\Models\ClientType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Student',
            'Outsider',
            'Faculty',
            'Dependent'
        ];

        foreach ($types as $type) {
            ClientType::create([
                'name' => $type,
            ]);
        }
    }
}
