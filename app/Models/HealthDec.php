<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthDec extends Model
{
    protected $table = 'healthdecmstr';

    protected $primaryKey = 'eid';

    public $timestamps = false;

    protected $fillable = [
        'refno',  'lastname',  'firstname',  'middlename',  'country',  'dateinp',  'gender',  'age',  'cno',  'work',  'addrwork',  'addrhome',  'email',  'reason',  'tempread',  'q1',  'q1ans',  'q2',  'q2ans',  'q3',  'q3ans',  'q4',  'q4ans',  'q5_1',  'q5_2',  'q5_3',  'q5_4',  'q5_5',  'q5_6',  'q5_7',  'q5_8',  'q5_9',  'q5_10',  'q5_11',  'datetimecreated'
    ];
}
