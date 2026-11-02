<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with sport_cpe_db initial data.
     */
    public function run(): void
    {
        $sqlPath = database_path('schema.sql');
        if (File::exists($sqlPath)) {
            DB::unprepared(File::get($sqlPath));
        }
    }
}

