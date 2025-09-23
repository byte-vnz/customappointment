<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    /**
     * Const Department Status
     *
     * @var array
     */
    const STATUSES = [
        0 => 'Inactive',
        1 => 'Active'
    ];

    /**
     * The primary key associated with the table
     *
     * @var string
     */
    protected $primaryKey = 'eid';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'department';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slot',
        'department_name',
        'dept_email',
        'status',
        'created_at'
    ];

    /**
     * Indicates if the model should be timestamp.
     *
     * @var bool
     */
    public $timestamps = false;
}
