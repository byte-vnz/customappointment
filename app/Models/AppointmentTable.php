<?php

namespace App\Models;

use App\Models\TransactionType;
use App\Models\Department;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;

class AppointmentTable extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'appmstr';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'eid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'refno',
        'lastname',
        'firstname',
        'middlename',
        'cno',
        'houseno',	
        'blockno',
        'lotno',	
        'unitno',	
        'floorno',	
        'addr1',	
        'addr2',				
        'addr3',	
        'emailadd',
        //'remarks',
        // 'bussname',
        'adate',
        'atime',
        'appstatus',
        'userid',
        'dtcreated',
        'timeslotid',
        'iscancel',
        'canceldt',
        'isonline',
        'departmentid',
        'transaction_type_id',
        'bin',
        'business_name',
        'settings_owner_model',
        'owner_uuid'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function genRefNo()
    {
        $ref = strtoupper(Str::random(7));

        if (empty($this->where('refno', $ref)->count())) {
            return $ref;
        } else {
            $this->genRefNo();
        }
    }

    public function slotReserveCount($date, $time)
    {
        $find = $this->whereDate('adate', $date)->where(['atime' => $time, 'iscancel' => 0])->count();

        return $find;
    }

    /**
     * Get the transaction type that owns the appointment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class);
    }

    /**
    * Get the department that owns the appointment
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function department()
    {
        return $this->belongsTo(Department::class, 'departmentid', 'eid');
    }

    /**
     * Scope a query to only include by walk-in priority settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWherePrioritySettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('walk_in_prio'));
    }

    /**
     * Scope a query to only include by walk-in non-priority settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereNonPrioritySettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('walk_in_non_prio'));
    }

    /**
     * Scope a query to only include by no bin filter settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereNoBinFilterSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('no_bin_filter'));
    }

    /**
     * Scope a query to only include by online settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereOnlineSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('online'));
    }


    public function bizInfo(String $bin)
    {
        $info = DB::connection('bplcs_conn')->select("SET NOCOUNT ON; EXEC spBUS_APIAppointment_Details ?", [$bin]);

        return $info[0] ?? $info;
    }

    /**
     * Scope a query to only include by online amendment settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereOnlineAmendmentSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('online_amendment'));
    }

    /**
     * Scope a query to only include by online retirement settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereOnlineRetirementSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('online_retirement'));
    }

    /**
     * Scope a query to only include by online certification settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereOnlineCertificationSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('online_certification'));
    }

    /**
     * Scope a query to only include by online mayors clearance settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereOnlineMayorsClearanceSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('online_mayors_clearance'));
    }

    /**
     * Scope a query to only include by online claim stub settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereOnlineClaimStubSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('online_claim_stub'));
    }

    /**
     * Scope a query to only include by online drop box settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereOnlineDropBoxSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('online_drop_box'));
    }

    /**
     * Scope a query to only include by walk-in renewal settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereWalkInRenewalSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('walk_in_renewal'));
    }
    /**
     * Scope a query to only include by walk-in renewal settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereWalkInNewSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('walk_in_new'));
    }    

    /**
     * Scope a query to only include by walk-in ayala renewal settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    
     public function scopeWhereAyalaWalkInRenewalSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('ayala_walk_in_renewal'));
    }
    
    /**
     * Scope a query to only include by walk-in Ayala renewal settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeWhereAyalaWalkInNewSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('ayala_walk_in_new'));
    }

    /**
     * Scope a query to only include by walk-in amendment settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereWalkInAmendmentSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('walk_in_amendment'));
    }

    /**
     * Scope a query to only include by walk-in retirement settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereWalkInRetirementSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('walk_in_retirement'));
    }

    /**
     * Scope a query to only include by walk-in certification settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereWalkInCertificationSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('walk_in_certification'));
    }

    /**
     * Scope a query to only include by walk-in mayors clearance settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereWalkInMayorsClearanceSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('walk_in_mayors_clearance'));
    }

    /**
     * Scope a query to only include by walk-in claim stub settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereWalkInClaimStubSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('walk_in_claim_stub'));
    }

    /**
     * Scope a query to only include by walk-in drop box settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereWalkInDropBoxSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('walk_in_drop_box'));
    }
    

    /**
     * Scope a query to only include by online renewal settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereOnlineRenewalSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('online_renewal'));
    }
    
    /**
     * Scope a query to only include by online new settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereOnlineNewSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('online_new'));
    }

    /**
     * Scope a query to only include by online ayala renewal settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereAyalaOnlineRenewalSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('ayala_online_renewal'));
    }
    /**
     * Scope a query to only include by online ayala New settings
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereAyalaOnlineNewSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('ayala_online_new'));
    }

    public function scopeWhereHelpcardOnlineSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('helpcard_online_site1'));
    }

    public function scopeWhereHelpcardWalkinSettings($query)
    {
        return $query->where('settings_owner_model', 'App\Models\Setting')
            ->where('owner_uuid', $this->getSettingUuid('helpcard_walkin_site1'));
    }



    public function showCountToday()
    {
        $q = $this->selectRaw('( count(*)) as Total')->addSelect('timeslotid', 'atime')->whereDate('adate', today())->where(['iscancel' => 0, 'transaction_type_id' => 13])->groupBy('timeslotid', 'atime')->get();
        return $q;
    }

    public function showCount($date)
    {
       // $q = $this->selectRaw('( count(*)) as Total')->addSelect('timeslotid', 'atime')->whereDate('adate', $date)->where(['iscancel' => 0, 'transaction_type_id' => 13])->groupBy('timeslotid', 'atime')->get();
       
       $q = $this->selectRaw('COUNT(*) as Total')
            ->addSelect('appmstr.timeslotid', 'appmstr.atime')
            ->join('timeslot', 'timeslot.tid', '=', 'appmstr.timeslotid')
            ->whereDate('appmstr.adate', $date)
            ->where([
                'appmstr.iscancel' => 0,
                'appmstr.transaction_type_id' => 13,
            ])
            ->where('timeslot.status', 1)
            ->groupBy('appmstr.timeslotid', 'appmstr.atime')
            ->get();

       return $q;
    }


    public function showRemainingCount($date)
        {
            $settingValue = \DB::table('settings')
                ->where('setting_uuid', '3c682e35-7e06-4397-9bc5-de15bd8a872e')
                ->value('value');

            return $this->select('appmstr.timeslotid', 'appmstr.atime')
                ->selectRaw('COUNT(*) - ? as Remaining', [$settingValue])
                ->join('timeslot', 'timeslot.tid', '=', 'appmstr.timeslotid')
                ->whereDate('appmstr.adate', $date)
                ->where('appmstr.iscancel', 0)
                ->where('appmstr.transaction_type_id', 13)
                ->where('timeslot.status', 1)
                ->groupBy('appmstr.timeslotid', 'appmstr.atime')
                ->get();
        }

        public function showRemainingCounts($date)
        {
            return $this->select('appmstr.timeslotid', 'appmstr.atime')
                ->selectRaw("
                    GREATEST(
                        (
                            SELECT `value` 
                            FROM `settings` 
                            WHERE `setting_uuid` = '3c682e35-7e06-4397-9bc5-de15bd8a872e'
                            LIMIT 1
                        ) - COUNT(*), 0
                    ) as Remaining
                ")
                ->join('timeslot', 'timeslot.tid', '=', 'appmstr.timeslotid')
                ->whereDate('appmstr.adate', $date)
                ->where('appmstr.iscancel', 0)
                ->where('appmstr.transaction_type_id', 13)
                ->where('timeslot.status', 1)
                ->groupBy('appmstr.timeslotid', 'appmstr.atime')
                ->get();
        }



      public function showCounttotal($date) 
    {
    // Subquery: grouped counts
    $sub = DB::table('appmstr')
        ->join('timeslot', 'timeslot.tid', '=', 'appmstr.timeslotid')
        ->whereDate('appmstr.adate', $date)
        ->where('appmstr.iscancel', 0)
        ->where('appmstr.transaction_type_id', 13)
        ->where('timeslot.status', 1)
        ->selectRaw('COUNT(*) as cnt')
        ->groupBy('appmstr.timeslotid', 'appmstr.atime');

    // Outer query: sum of grouped counts
    $r = DB::table(DB::raw("({$sub->toSql()}) as sub"))
        ->mergeBindings($sub) // keep bindings like $date
        ->selectRaw('SUM(cnt) as Total')
        ->value('Total');

    return $r; // 
    }

    /**
     * Get setting uuid by keys
     *
     * @param string $key
     * @return string
     */
    private function getSettingUuid($key)
    {
        return Setting::where('key', Setting::KEYS[$key])
            ->pluck('setting_uuid')
            ->first();
    }
}