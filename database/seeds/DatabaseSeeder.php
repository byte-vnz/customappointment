<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Table Seeder
        $this->call(TransactionTypesTableSeeder::class);

        // Settings Table Seeder
        $this->call(SettingsTableSeeder::class);
        $this->call(BinSchedulesTableSeeder::class);
    }
}
