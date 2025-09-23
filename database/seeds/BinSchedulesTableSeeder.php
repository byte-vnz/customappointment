<?php

use Illuminate\Database\Seeder;
use App\Helpers\SettingHelper;

class BinSchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Bin Schedule Settings For No Bin Filter in Online and Walk-In
        SettingHelper::set([
            'name' => 'No Bin Filter',
            'key' => 'appointment.no_bin_filter',
            'value' => '',
            'date_from' => '2021-01-01',
            'date_to' => '2021-01-24'
        ], 'bin');
        SettingHelper::set([
            'name' => 'No Bin Filter',
            'key' => 'appointment.no_bin_filter',
            'value' => '',
            'date_from' => '2021-03-01',
            'date_to' => '2021-12-31'
        ], 'bin');

        // Bin Schedule Settings For Walk-In
        SettingHelper::set([
            'name' => 'Priority Walk-In Bin Ending',
            'key' => 'appointment.walk_in_priority',
            'value' => '["1","2"]',
            'date_from' => '2021-01-25',
            'date_to' => '2021-01-31'
        ], 'bin');
        SettingHelper::set([
            'name' => 'Priority Walk-In Bin Ending',
            'key' => 'appointment.walk_in_priority',
            'value' => '["3","4"]',
            'date_from' => '2021-02-01',
            'date_to' => '2021-02-07'
        ], 'bin');
        SettingHelper::set([
            'name' => 'Priority Walk-In Bin Ending',
            'key' => 'appointment.walk_in_priority',
            'value' => '["5","6"]',
            'date_from' => '2021-02-08',
            'date_to' => '2021-02-14'
        ], 'bin');
        SettingHelper::set([
            'name' => 'Priority Walk-In Bin Ending',
            'key' => 'appointment.walk_in_priority',
            'value' => '["7","8"]',
            'date_from' => '2021-02-15',
            'date_to' => '2021-02-21'
        ], 'bin');
        SettingHelper::set([
            'name' => 'Priority Walk-In Bin Ending',
            'key' => 'appointment.walk_in_priority',
            'value' => '["9","0"]',
            'date_from' => '2021-02-22',
            'date_to' => '2021-02-28'
        ], 'bin');

        // Bin Schedule Settings For Online
        SettingHelper::set([
            'name' => 'Online - Accommodate ONLY Bin Ending',
            'key' => 'appointment.online',
            'value' => '["1","2"]',
            'date_from' => '2021-01-25',
            'date_to' => '2021-01-31'
        ], 'bin');
        SettingHelper::set([
            'name' => 'Online - Accommodate ONLY Bin Ending',
            'key' => 'appointment.online',
            'value' => '["3","4"]',
            'date_from' => '2021-02-01',
            'date_to' => '2021-02-07'
        ], 'bin');
        SettingHelper::set([
            'name' => 'Online - Accommodate ONLY Bin Ending',
            'key' => 'appointment.online',
            'value' => '["5","6"]',
            'date_from' => '2021-02-08',
            'date_to' => '2021-02-14'
        ], 'bin');
        SettingHelper::set([
            'name' => 'Online - Accommodate ONLY Bin Ending',
            'key' => 'appointment.online',
            'value' => '["7","8"]',
            'date_from' => '2021-02-15',
            'date_to' => '2021-02-21'
        ], 'bin');
        SettingHelper::set([
            'name' => 'Online - Accommodate ONLY Bin Ending',
            'key' => 'appointment.online',
            'value' => '["9","0"]',
            'date_from' => '2021-02-22',
            'date_to' => '2021-02-28'
        ], 'bin');

        // Bin Schedule Settings in Business Permit For Renewal Transaction Type in Online Appointment
        SettingHelper::set([
            'name' => 'Online - Accommodate ONLY Renewal Transaction Type',
            'key' => 'appointment.online.renewal',
            'value' => '',
            'date_from' => '2021-01-01',
            'date_to' => '2021-01-25'
        ], 'bin');

        // Bin Schedule Settings in Business Permit For Renewal Transaction Type in Walk-In Appointment
        SettingHelper::set([
            'name' => 'Walk-In - Accommodate ONLY Renewal Transaction Type',
            'key' => 'appointment.walk_in.renewal',
            'value' => '',
            'date_from' => '2021-01-01',
            'date_to' => '2021-01-25'
        ], 'bin');

        // Bin Schedule Settings in Business Permit For Renewal Transaction Type in Online Appointment - March to Dec
        SettingHelper::set([
            'name' => 'Online - Accommodate ONLY Renewal Transaction Type',
            'key' => 'appointment.online.renewal',
            'value' => '',
            'date_from' => '2021-03-01',
            'date_to' => '2021-12-31'
        ], 'bin');

        // Bin Schedule Settings in Business Permit For Renewal Transaction Type in Walk-In Appointment - March to Dec
        SettingHelper::set([
            'name' => 'Walk-In - Accommodate ONLY Renewal Transaction Type',
            'key' => 'appointment.walk_in.renewal',
            'value' => '',
            'date_from' => '2021-03-01',
            'date_to' => '2021-12-31'
        ], 'bin');
    }
}
