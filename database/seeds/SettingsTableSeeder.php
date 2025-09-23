<?php

use Illuminate\Database\Seeder;
use App\Helpers\SettingHelper;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Default Settings
        SettingHelper::set([
            'name' => 'Default Online Appointment Slot',
            'key' => 'appointment.online',
            'value' => 30
        ]);
        SettingHelper::set([
            'name' => 'Default Walk-In Priority Appointment Slot',
            'key' => 'appointment.walk_in_priority',
            'value' => 10
        ]);
        SettingHelper::set([
            'name' => 'Default Walk-In Non-Priority Appointment Slot',
            'key' => 'appointment.walk_in_non_priority',
            'value' => 20
        ]);
        SettingHelper::set([
            'name' => 'Default No Bin Filter Appointment Slot',
            'key' => 'appointment.no_bin_filter',
            'value' => 60
        ]);
        // Default Settings in Business Permit Per Transaction Types in Online Appointment
        SettingHelper::set([
            'name' => 'Default Online Amendment Appointment Slot',
            'key' => 'appointment.online.amendment',
            'value' => 4
        ]);
        SettingHelper::set([
            'name' => 'Default Online Retirement Appointment Slot',
            'key' => 'appointment.online.retirement',
            'value' => 4
        ]);
        SettingHelper::set([
            'name' => 'Default Online Certification Appointment Slot',
            'key' => 'appointment.online.certification',
            'value' => 4
        ]);
        SettingHelper::set([
            'name' => 'Default Online Mayors Clearance Appointment Slot',
            'key' => 'appointment.online.mayors_clearance',
            'value' => 4
        ]);
        SettingHelper::set([
            'name' => 'Default Online Claim Stub Appointment Slot',
            'key' => 'appointment.online.claim_stub',
            'value' => 10
        ]);
        SettingHelper::set([
            'name' => 'Default Online Drop Box Appointment Slot',
            'key' => 'appointment.online.drop_box',
            'value' => 10
        ]);
        // Default Settings in Business Permit Per Transaction Types in Walk-In Appointment
        SettingHelper::set([
            'name' => 'Default Walk-In Amendment Appointment Slot',
            'key' => 'appointment.walk_in.amendment',
            'value' => 1
        ]);
        SettingHelper::set([
            'name' => 'Default Walk-In Retirement Appointment Slot',
            'key' => 'appointment.walk_in.retirement',
            'value' => 1
        ]);
        SettingHelper::set([
            'name' => 'Default Walk-In Certification Appointment Slot',
            'key' => 'appointment.walk_in.certification',
            'value' => 1
        ]);
        SettingHelper::set([
            'name' => 'Default Walk-In Mayors Clearance Appointment Slot',
            'key' => 'appointment.walk_in.mayors_clearance',
            'value' => 1
        ]);
        SettingHelper::set([
            'name' => 'Default Walk-In Claim Stub Appointment Slot',
            'key' => 'appointment.walk_in.claim_stub',
            'value' => 10
        ]);
        SettingHelper::set([
            'name' => 'Default Walk-In Drop Box Appointment Slot',
            'key' => 'appointment.walk_in.drop_box',
            'value' => 10
        ]);
        // Default Settings Walk-In Grace Period
        SettingHelper::set([
            'name' => 'Default Walk-In Appointment Grace Period',
            'key' => 'appointment.walk_in.grace_period',
            'value' => 45
        ]);
        // Default Weekend Saturday Allowed Appointment
        SettingHelper::set([
            'name' => 'Default Weekend Saturday Allowed Appointment',
            'key' => 'appointment.weekend.saturday',
            'value' => '2021-01-01 - 2021-02-28'
        ]);
        // Default Weekend Sunday Allowed Appointment
        SettingHelper::set([
            'name' => 'Default Weekend Sunday Allowed Appointment',
            'key' => 'appointment.weekend.sunday',
            'value' => '2021-01-01 - 2021-02-28'
        ]);
        // Default Settings in Business Permit For Renewal Transaction Type in Online Appointment
        SettingHelper::set([
            'name' => 'Default Online Renewal Appointment ',
            'key' => 'appointment.online.renewal',
            'value' => 50
        ]);
        // Default Settings in Business Permit For Renewal Transaction Type in Walk-In Appointment
        SettingHelper::set([
            'name' => 'Default Walk-In Renewal Appointment ',
            'key' => 'appointment.walk_in.renewal',
            'value' => 10
        ]);
    }
}
