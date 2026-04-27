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
            'JSS1', 'JSS2', 'JSS3',
            'SSS1', 'SSS2', 'SSS3'
        ];

        foreach ($classes as $class) {
            SchoolClass::firstOrCreate(['name' => $class]);
        }
    }
}
