<?php

namespace App\Models;

use Carbon\Carbon;
use Rollswan\Uuid\Traits\WithUuid;
use Illuminate\Database\Eloquent\Model;

class BinSchedule extends Model
{
    use WithUuid;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'bin_schedule_uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'key',
        'value',
        'date_from',
        'date_to'
    ];
}
