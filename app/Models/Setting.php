<?php

namespace App\Models;

use Rollswan\Uuid\Traits\WithUuid;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use WithUuid;

    /**
     * Const Setting Keys
     *
     * @var array
     */
    const KEYS = [
        'online' => 'appointment.online',
        'walk_in_prio' => 'appointment.walk_in_priority',
        'walk_in_non_prio' => 'appointment.walk_in_non_priority',
        'no_bin_filter' => 'appointment.no_bin_filter',
        'online_amendment' => 'appointment.online.amendment',
        'online_retirement' => 'appointment.online.retirement',
        'online_certification' => 'appointment.online.certification',
        'online_mayors_clearance' => 'appointment.online.mayors_clearance',
        'online_claim_stub' => 'appointment.online.claim_stub',
        'online_drop_box' => 'appointment.online.drop_box',
        'walk_in_amendment' => 'appointment.walk_in.amendment',
        'walk_in_retirement' => 'appointment.walk_in.retirement',
        'walk_in_certification' => 'appointment.walk_in.certification',
        'walk_in_mayors_clearance' => 'appointment.walk_in.mayors_clearance',
        'walk_in_claim_stub' => 'appointment.walk_in.claim_stub',
        'walk_in_drop_box' => 'appointment.walk_in.drop_box',
        'walk_in_grace_period' => 'appointment.walk_in.grace_period',
        'weekend_saturday' => 'appointment.weekend.saturday',
        'weekend_sunday' => 'appointment.weekend.sunday',
        'online_renewal' => 'appointment.online.renewal',
        'walk_in_renewal' => 'appointment.walk_in.renewal',
        'online_new' => 'appointment.online.new',
        'walk_in_new' => 'appointment.walk_in.new',
        'ayala_online_renewal' => 'appointment.online.ayala_renewal',
        'ayala_walk_in_renewal' => 'appointment.walk_in.ayala_renewal',
        'ayala_online_new' => 'appointment.online.ayala_new',
        'ayala_walk_in_new' => 'appointment.walk_in.ayala_new',
        'helpcard_online_site1' => 'appointment.online.helpcardsite1',
        'helpcard_walkin_site1'  => 'appointment.walk_in.helpcardsite2'

    ];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'setting_uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'key',
        'value'
    ];
}