<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolClass;

class SchoolClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            'SSS1A', 'SSS1B', 'SSS1C', 'SSS1D', 'SSS1E', 'SSS1F',
            'SSS2A', 'SSS2B', 'SSS2C', 'SSS2D', 'SSS2E', 'SSS2F',
            'SSS3A', 'SSS3B', 'SSS3C', 'SSS3D', 'SSS3E', 'SSS3F',
        ];

        foreach ($classes as $class) {
            SchoolClass::firstOrCreate(['name' => $class]);
        }
    }
}
