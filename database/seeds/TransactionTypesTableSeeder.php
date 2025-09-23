<?php

use App\Models\TransactionType;
use Illuminate\Database\Seeder;

class TransactionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $renewal = TransactionType::firstOrNew([
            'name' => 'Renewal',
        ]);

        $renewal->save();

        $rap = TransactionType::firstOrNew([
            'name' => 'Rap',
        ]);

        $rap->save();

        $clearance = TransactionType::firstOrNew([
            'name' => 'Mayors clearance / certification',
        ]);

        $clearance->save();

        $amendment = TransactionType::firstOrNew([
            'name' => 'Amendment',
        ]);

        $amendment->save();
    }
}
