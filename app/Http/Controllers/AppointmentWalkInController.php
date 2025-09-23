<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateAppointmentWalkInRequest;
use App\Helpers\StringHelper;
use App\Helpers\SettingHelper;
use Carbon\Carbon;
use App\Http\Controllers\Appointment;
use App\Models\Setting;
use App\Models\Department;
use App\Models\TransactionType;
use App\Models\TimeInterval;
use App\Models\AppointmentTable;
use App\Models\BinSchedule;
use App\Models\Holiday;

class AppointmentWalkInController extends Controller
{
    private $appointmentKey = '';

    /**
    * Shows filing of appointment for walk-in.
    *
    * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    */
    public function index()
    {
        // Prepare the data
        $departments = Department::where('status', 1)->get();
        $timeIntervals = TimeInterval::where('status', 1)->get();
        $transactiontype = TransactionType::where('id', 13)->get();

        // We're done
        return view('modules.appointment.walk-in.index', compact('departments', 'timeIntervals','transactiontype'));
    }

    /**
    * Creates a new appointment.
    *
    * @param CreateAppointmentWalkInRequest $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function create(CreateAppointmentWalkInRequest $request)
    {
        // Prepare the data
        $appointment = new AppointmentTable();
        $requestSchedDate = Carbon::createFromFormat('Y-m-d', $request->get('adate'));

        // Check friday cut off
        if ($requestSchedDate->isFriday()) {
            if (!$this->checkFridayCutOffSettings($request)) {
                session()->flash('error', '<b>Sorry,</b> the Business Permits and Licensing Office will undergo a scheduled decontamination on your selected date of appointment.');
                return back()->withInput($request->except('_token'));
            
            }
        }

        // Check weekends allowed appointments
        if ($requestSchedDate->isSaturday() || $requestSchedDate->isSunday()) {
            if (!$this->checkWeekendSettings($request, $requestSchedDate->dayOfWeek)) {
                session()->flash('error', '<b>Sorry,</b> the office is close for the appointment date you selected.');
                return back()->withInput($request->except('_token'));
            }
        }

        // Check if holiday


        if ($this->isHoliday($request->adate)) {
            
            session()->flash('error', '<b>Sorry,</b> the date you selected for the appointment is holiday.');
            return back()->withInput($request->except('_token'));
        }

        // Check Available Slots For Business Permit (eid = 2), and Transaction Types ID ranges ONLY to 1 - 7
        if ($request->departmentid == 2 && $request->transaction_type_id >= 1 && $request->transaction_type_id <= 8) {

            if ($request->transaction_type_id == 1) {
                // Check if valid appointment
                if (!$this->getConfigBinSchedKey($request->bin, $request->adate)) {
//<br>Allowed BIN ending for extension <br><br>1-2 January 23 - January 29 <br>3-4 January 30 - Feburary 5 <br>5-6 Feburary 6 - Feburary 12 <br>7-8 Feburary 13 - Feburary 19 <br>9-0 Feburary 20 - Feburary 26 <br>
                    session()->flash('error', '<b>Sorry,</b> the date you selected for the appointment does not fall on the scheduled extension for your BIN.');
                    return back()->withInput($request->except('_token'));
                }

            }
            // Get available slots
            $availableSlots = $this->getBusinessPermitAvailableSlots($request);
        } else {
            // Set appointment key
            $this->appointmentKey = $this->getConfigBinSchedKey($request->bin, $request->adate);

            // Get available slots
            $availableSlots = $this->getAvailableSlots($request);
        }

        // Get config owner uuid
        $ownerUuid = $this->getConfigOwnerUuid();

        // Check for available slots
        if ($availableSlots <= 0) {
            session()->flash('error', '<b>Sorry.</b> There is no available slots for the requested schedule.');
            return back()->withInput($request->except('_token'));
        }

        // Create appointment
        $createAppointment = AppointmentTable::create(array_merge($request->except('_token'), [
            'emailadd' => '',
            'refno' => $appointment->genRefNo(),
            'dtcreated' => Carbon::now(),
            'timeslotid' => TimeInterval::where('timeslot', $request->atime)->first()->tid,
            'isonline' => 0,
            'userid' => 0,
            'appstatus' => 0,
            'settings_owner_model' => 'App\Models\Setting',
            'owner_uuid' => $ownerUuid
        ]));

        // Generate QR Code
        $appointmentController = new Appointment(new AppointmentTable, new TimeInterval);
        $appointmentController->generateQRCode($createAppointment->eid);

        // We're done
        session()->flash('success', '<b>Submission successful!</b> Your preferred schedule has been sent.');
        session()->flash('redirect', route('appointment.walk-in.view', ['refno' => $createAppointment->refno]));
        return back();
    }

    /**
     * View Appointment Info
     *
     * @param string $refno
     * @return \Illuminate\View\View
     */
    public function viewAppointment($refno)
    {
        // Get appointment data
        $appointment = AppointmentTable::where('refno', $refno)->first();

        // We're done
        return response()->json([
            'message' => null,
            'result' => $appointment
        ], 200);
    }

    /**
     * Pull apopointment available slot
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pullAvailableSlots(Request $request)
    {
        $request->validate([
            'adate' => ['required', 'date'],
            'atime' => ['required', 'exists:timeslot,timeslot'],
            //'bin' => ['nullable', 'string'],
            //'transaction_type_id' => ['required', 'integer', 'exists:transaction_types,id'],
            'departmentid' => ['required', 'integer', 'exists:department,eid']
        ], [], [
            'adate' => 'date',
            'atime' => 'timeslot',
            //'bin' => 'bin',
            //'transaction_type_id' => 'transaction type',
            'departmentid' => 'office'
        ]);

        // Check Available Slots For Business Permit (eid = 2), and Transaction Types ID ranges ONLY to 1 - 8
        if ($request->departmentid == 2 && $request->transaction_type_id >= 1 && $request->transaction_type_id <= 8) {
            // Get available slots
            $availableSlots = $this->getBusinessPermitAvailableSlots($request);

        } else if ($req->departmentid == 23 && $req->transaction_type_id == 12) {
            // Get available slots
            $availableSlots = $this->getAyalaAvailableSlots($req);

        } else if ($req->departmentid == 24 && $req->transaction_type_id == 20) {
            // Get available slots for HELPCARDSITE1 
          
            $availableSlots = $this->getHelpcardSite1AvailableSlots($req);
        } 
    
        
        
        else {
            // Set appointment key
            $this->appointmentKey = $this->getConfigBinSchedKey($request->bin, $request->adate);

            // Get available slots
            $availableSlots = $this->getAvailableSlots($request);
        }

        // Check for available slots
        if ($availableSlots <= 0) {
            $msg = "There's no available slot(s).";
        } else {
            $msg = "$availableSlots available slot(s).";
        }

        // We're done
        return response()->json(['message' => $msg]);
    }

    /**
     * Get Config Bin Schedule Key using Bin and Request Date
     *
     * @param string $binNumber
     * @param string $date
     * @return bool
     */
    private function getConfigBinSchedKey($binNumber, $date)
    {
        // Prepare the data
        $requestSchedDate = Carbon::createFromFormat('Y-m-d', $date);
        $binEnding = StringHelper::getLastChar($binNumber);

        // Get Bin Schedule Settings
        $data = BinSchedule::where('key', Setting::KEYS['walk_in_prio'])
            ->orderBy('date_from')
            ->get();

        // Iterates through data
        $collections = collect($data);
        $binSchedule = new BinSchedule();
        $collections->each(function ($item, $key) use ($requestSchedDate, &$binSchedule) {
            $startDate = Carbon::createFromFormat('Y-m-d', $item->date_from);
            $endDate = Carbon::createFromFormat('Y-m-d', $item->date_to);

            // Check if between config date range
            if ($requestSchedDate->between($startDate, $endDate)) {
                $binSchedule = $item;
            }
        });

       // $str_pos_check = (!empty($binSchedule->value) && !empty($binEnding)) ? strpos($binSchedule->value, $binEnding) : false;
        $str_pos_check = (!empty($binSchedule->value) ) ? strpos($binSchedule->value, $binEnding) : false;
       
        if (!$binSchedule || $str_pos_check === false) {
            $noBinFilters = BinSchedule::where('key', Setting::KEYS['no_bin_filter'])
                ->orderBy('date_from')
                ->get();

            foreach ($noBinFilters as $noBinFilter) {
                // Check if between config date range in no bin filter
                if ($requestSchedDate->between(Carbon::createFromFormat('Y-m-d', $noBinFilter->date_from), Carbon::createFromFormat('Y-m-d', $noBinFilter->date_to))) {
                    return $noBinFilter->key;
                }
            }

            
            //return false;
            return Setting::KEYS['walk_in_non_prio'];
        }

        // We're done
        
        return $binSchedule->key;
        return true;
        
    }

/**
     * Get ayala available slots for transaction type 9
     *
     *  @param CreateAppointmentWalkInRequest $request
     * @return int
     */
    private function getAyalaAvailableSlots($request)
    {
        // Prepare the data
        $isValid = false;
        $requestSchedDate = Carbon::createFromFormat('Y-m-d', $request->get('adate'));
        $binSchedules = BinSchedule::where('key', Setting::KEYS['ayala_walk_in_renewal'])
            ->orderBy('date_from')
            ->get();

        foreach ($binSchedules as $binSchedule) {
            // Check if between config date range
            if ($requestSchedDate->between(Carbon::createFromFormat('Y-m-d', $binSchedule->date_from), Carbon::createFromFormat('Y-m-d', $binSchedule->date_to))) {
                // RENEWAL For No Bin Filter
                $isValid = true;
                $configWalkInSlots = SettingHelper::get(Setting::KEYS['ayala_walk_in_renewal']);
                $configOnlineSlots = SettingHelper::get(Setting::KEYS['ayala_online_renewal']);
                $reserveWalkInSlots = AppointmentTable::whereAyalaWalkInRenewalSettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();
                $reserveOnlineSlots = AppointmentTable::whereAyalaOnlineRenewalSettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();

                // Set appointment key
                $this->appointmentKey = Setting::KEYS['ayala_walk_in_renewal'];

                // Compute available slots
                $availableSlots = ((int)$configWalkInSlots + (int)$configOnlineSlots) - ((int)$reserveWalkInSlots + (int)$reserveOnlineSlots);
            }
        }

        // RENEWAL With Bin Filter
        if (!$isValid) {
            // Set appointment key
            $this->appointmentKey = $this->getConfigBinSchedKey($request->bin, $request->adate);

            // Get available slots
            $availableSlots = $this->getAvailableSlots($request);
        }
        
        return $availableSlots; 
    }

    /**
     * Get available slots
     *
     * @param CreateAppointmentWalkInRequest $request
     * @return int
     */
    private function getAvailableSlots($request)
    {
        switch ($this->appointmentKey) {
            case Setting::KEYS['walk_in_prio']:
                $configPrioritySlots = SettingHelper::get(Setting::KEYS['walk_in_prio']);
                $configOnlineSlots = SettingHelper::get(Setting::KEYS['online']);
                $reservePrioritySlots = AppointmentTable::wherePrioritySettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();
                $reserveOnlineSlots = AppointmentTable::whereOnlineSettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();
                $availableSlots = ((int)$configPrioritySlots + (int)$configOnlineSlots) - ((int)$reservePrioritySlots + (int)$reserveOnlineSlots);
                break;
            case Setting::KEYS['walk_in_non_prio']:
                $configSlots = SettingHelper::get(Setting::KEYS['walk_in_non_prio']);
                $reserveSlots = AppointmentTable::whereNonPrioritySettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();
                $availableSlots = (int)$configSlots - (int)$reserveSlots;
                break;
            case Setting::KEYS['no_bin_filter']:
                $configSlots = SettingHelper::get(Setting::KEYS['no_bin_filter']);
                $reserveSlots = AppointmentTable::whereNoBinFilterSettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();
                $availableSlots = (int)$configSlots - (int)$reserveSlots;
                break;
            case Setting::KEYS['helpcard_walkin_site1']:
                $configSlots = SettingHelper::get(Setting::KEYS['helpcard_walkin_site1']);
                $reserveSlots = AppointmentTable::HelpcardWalkinSettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();
                $availableSlots = (int)$configSlots - (int)$reserveSlots;
                break;
        }

        // We're done
        return $availableSlots;
    }

    /**
    * Get Settings Uuid
    *
    * @return string
    */
    private function getConfigOwnerUuid()
    {
        // Prepare the data
        $configUuid = Setting::where('key', $this->appointmentKey)
            ->pluck('setting_uuid')
            ->first();

        // We're done
        return $configUuid;
    }

    /**
     * Get business permit available slots for transaction types ranges 1 - 8
     *
     *  @param CreateAppointmentWalkInRequest $request
     * @return int
     */
    private function getBusinessPermitAvailableSlots($request)
    {
        switch ($request->transaction_type_id) {
            case '1':
                // Prepare the data
                $isValid = false;
                $requestSchedDate = Carbon::createFromFormat('Y-m-d', $request->get('adate'));
                $binSchedules = BinSchedule::where('key', Setting::KEYS['walk_in_renewal'])
                    ->orderBy('date_from')
                    ->get();

                foreach ($binSchedules as $binSchedule) {
                    // Check if between config date range
                    if ($requestSchedDate->between(Carbon::createFromFormat('Y-m-d', $binSchedule->date_from), Carbon::createFromFormat('Y-m-d', $binSchedule->date_to))) {
                        // RENEWAL For No Bin Filter
                        $isValid = true;
                        $configWalkInSlots = SettingHelper::get(Setting::KEYS['walk_in_renewal']);
                        $configOnlineSlots = SettingHelper::get(Setting::KEYS['online_renewal']);
                        $reserveWalkInSlots = AppointmentTable::whereWalkInRenewalSettings()
                            ->whereDate('adate', $request->get('adate'))
                            ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                            ->count();
                        $reserveOnlineSlots = AppointmentTable::whereOnlineRenewalSettings()
                            ->whereDate('adate', $request->get('adate'))
                            ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                            ->count();

                        // Set appointment key
                        $this->appointmentKey = Setting::KEYS['walk_in_renewal'];

                        // Compute available slots
                        $availableSlots = ((int)$configWalkInSlots + (int)$configOnlineSlots) - ((int)$reserveWalkInSlots + (int)$reserveOnlineSlots);
                    }
                }

                // RENEWAL With Bin Filter
                if (!$isValid) {
                    // Set appointment key
                    $this->appointmentKey = $this->getConfigBinSchedKey($request->bin, $request->adate);

                    // Get available slots
                    $availableSlots = $this->getAvailableSlots($request);
                }
                break;
            case '2':
                // AMENDMENT
                $configSlots = SettingHelper::get(Setting::KEYS['walk_in_amendment']);
                $reserveSlots = AppointmentTable::whereWalkInAmendmentSettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();

                // Set appointment key
                $this->appointmentKey = Setting::KEYS['walk_in_amendment'];

                // Compute available slots
                $availableSlots = (int)$configSlots - (int)$reserveSlots;
                break;
            case '3':
                // RETIREMENT
                $configSlots = SettingHelper::get(Setting::KEYS['walk_in_retirement']);
                $reserveSlots = AppointmentTable::whereWalkInRetirementSettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();

                // Set appointment key
                $this->appointmentKey = Setting::KEYS['walk_in_retirement'];

                // Compute available slots
                $availableSlots = (int)$configSlots - (int)$reserveSlots;
                break;
            case '4':
                // CERTIFICATION
                $configSlots = SettingHelper::get(Setting::KEYS['walk_in_certification']);
                $reserveSlots = AppointmentTable::whereWalkInCertificationSettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();

                // Set appointment key
                $this->appointmentKey = Setting::KEYS['walk_in_certification'];

                // Compute available slots
                $availableSlots = (int)$configSlots - (int)$reserveSlots;
                break;
            case '5':
                // MAYOR'S CLEARANCE
                $configSlots = SettingHelper::get(Setting::KEYS['walk_in_mayors_clearance']);
                $reserveSlots = AppointmentTable::whereWalkInMayorsClearanceSettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();

                // Set appointment key
                $this->appointmentKey = Setting::KEYS['walk_in_mayors_clearance'];

                // Compute available slots
                $availableSlots = (int)$configSlots - (int)$reserveSlots;
                break;
            case '6':
                // CLAIM STUB
                $configSlots = SettingHelper::get(Setting::KEYS['walk_in_claim_stub']);
                $reserveSlots = AppointmentTable::whereWalkInClaimStubSettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();

                // Set appointment key
                $this->appointmentKey = Setting::KEYS['walk_in_claim_stub'];

                // Compute available slots
                $availableSlots = (int)$configSlots - (int)$reserveSlots;
                break;
            case '7':
                // DROP BOX
                $configSlots = SettingHelper::get(Setting::KEYS['walk_in_drop_box']);
                $reserveSlots = AppointmentTable::whereWalkInDropBoxSettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();

                // Set appointment key
                $this->appointmentKey = Setting::KEYS['walk_in_drop_box'];

                // Compute available slots
                $availableSlots = (int)$configSlots - (int)$reserveSlots;
                break;
            case '8':
                    // NEW
                    $configSlots = SettingHelper::get(Setting::KEYS['walk_in_new']);
                    $reserveSlots = AppointmentTable::whereWalkInNewSettings()
                        ->whereDate('adate', $request->get('adate'))
                        ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                        ->count();
    
                    // Set appointment key
                    $this->appointmentKey = Setting::KEYS['walk_in_new'];
    
                    // Compute available slots
                    $availableSlots = (int)$configSlots - (int)$reserveSlots;
                    break;
        }

        return $availableSlots;
    }

    /**
     * Check if holiday
     *
     * @param date $date
     * @return int
     */
    private function isHoliday($date)
    {
        // Prepare the data
        $holiday = Holiday::whereHoliday($date)->first();

        if (!$holiday) {
            return false;
        }

        return true;
    }

    /**
     * Check weekend settings
     *
     * @param Request $request
     * @param int $weekend
     * @return bool
     */
    private function checkWeekendSettings($request, $weekend)
    {
        // Prepare the data
        $requestSchedDate = Carbon::createFromFormat('Y-m-d', $request->get('adate'));
        $configWeekendSaturday = SettingHelper::get(Setting::KEYS['weekend_saturday']);
        $configWeekendSunday = SettingHelper::get(Setting::KEYS['weekend_sunday']);

        // 0 (sunday) and 6 (saturday)
        switch ($weekend) {
            case 6:
                // Saturday allowed date range
                list($startDate, $endDate) = explode(' - ', $configWeekendSaturday);
                $startDate = Carbon::createFromFormat('Y-m-d', $startDate);
                $endDate = Carbon::createFromFormat('Y-m-d', $endDate);

                // Check if between config date range
                if ($requestSchedDate->between($startDate, $endDate)) {
                    return true;
                }
                break;
            case 0:
                // Sunday allowed date range
                list($startDate, $endDate) = explode(' - ', $configWeekendSunday);
                $startDate = Carbon::createFromFormat('Y-m-d', $startDate);
                $endDate = Carbon::createFromFormat('Y-m-d', $endDate);

                // Check if between config date range
                if ($requestSchedDate->between($startDate, $endDate)) {
                    // Parse selected timeslot
                    list($startTimeSlot, $endTimeSlot) = explode(' - ', $request->get('atime'));
                    $startTimeSlot = $requestSchedDate->format('Y-m-d') . ' ' . Carbon::createFromFormat('g:i A', $startTimeSlot)->format('H:i:s');
                    $endTimeSlot = $requestSchedDate->format('Y-m-d') . ' ' . Carbon::createFromFormat('g:i A', $endTimeSlot)->format('H:i:s');
                    $cutOff = Carbon::parse($requestSchedDate->format('Y-m-d') . ' 11:00:00');

                    if (Carbon::parse($endTimeSlot)->lte($cutOff)) {
                        return true;
                    }
                }
                break;
        }

        // We're done
        return false;
    }

    /**
     * Check friday cut off settings
     *
     * @param Request $request
     * @return bool
     */
    private function checkFridayCutOffSettings($request)
    {
        // Prepare the data
        $requestSchedDate = Carbon::createFromFormat('Y-m-d', $request->get('adate'));
        list($startTime, $endTime) = explode(' - ', $request->get('atime'));
        $startTime = $requestSchedDate->format('Y-m-d') . ' ' . Carbon::createFromFormat('g:i A', $startTime)->format('H:i:s');
        $endTime = $requestSchedDate->format('Y-m-d') . ' '. Carbon::createFromFormat('g:i A', $endTime)->format('H:i:s');
        $fridayCufOff = Carbon::parse($requestSchedDate->format('Y-m-d') . ' 23:00:00');

        if (Carbon::parse($endTime)->gt($fridayCufOff)) {
            return false;
        }

        // We're done
        return true;
    }

    private function hasRenewalSettings($request)
    {
        // Prepare the data
        $requestSchedDate = Carbon::createFromFormat('Y-m-d', $request->get('adate'));
        $binSchedules = BinSchedule::where('key', Setting::KEYS['online_renewal'])
            ->orderBy('date_from')
            ->get();
        

        foreach ($binSchedules as $binSchedule) {
            // Check if between config date range
            if ($requestSchedDate->between(Carbon::createFromFormat('Y-m-d', $binSchedule->date_from), Carbon::createFromFormat('Y-m-d', $binSchedule->date_to))) {
                // RENEWAL For No Bin Filter
                return true;
            }
        }

        // We're done
        return false;
    }

    private function isValidAppointment($binNumber, $date)
    {
        // Prepare the data
        $requestSchedDate = Carbon::createFromFormat('Y-m-d', $date);
        $binEnding = StringHelper::getLastChar($binNumber);

        // Get Online Bin Schedule
        $data = BinSchedule::where('key', Setting::KEYS['online'])
            ->orderBy('date_from')
            ->get();

        // Iterates through data
        $collections = collect($data);
        $binSchedule = new BinSchedule();
        $collections->each(function ($item, $key) use ($requestSchedDate, &$binSchedule) {
            $startDate = Carbon::createFromFormat('Y-m-d', $item->date_from);
            $endDate = Carbon::createFromFormat('Y-m-d', $item->date_to);

            // Check if between config date range
            if ($requestSchedDate->between($startDate, $endDate)) {
                $binSchedule = $item;
            }
        });

       //$str_pos_check = (!empty($binSchedule->value) && !empty($binEnding)) ? strpos($binSchedule->value, $binEnding) : false;
       $str_pos_check = (!empty($binSchedule->value) ) ? strpos($binSchedule->value, $binEnding) : false;
        if (!$binSchedule || $str_pos_check === false) {
            $noBinFilters = BinSchedule::where('key', Setting::KEYS['no_bin_filter'])
                ->orderBy('date_from')
                ->get();

            foreach ($noBinFilters as $noBinFilter) {
                if ($requestSchedDate->between(Carbon::createFromFormat('Y-m-d', $noBinFilter->date_from), Carbon::createFromFormat('Y-m-d', $noBinFilter->date_to))) {

                    // Set appointment key
                    //$this->appointmentKey = $noBinFilter->key;
                    return true;
                }
            }

            return false;
        }

        // Set appointment key
        //$this->appointmentKey = $binSchedule->key;

        // We're done
        return true;
    }

    
}