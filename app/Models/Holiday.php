<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'date',
        'type'
    ];

    /**
     * Scope a query to only include by holiday
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param date $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereHoliday($query, $date)
    {
        return $query->whereDate('date', $date);
    }
}
