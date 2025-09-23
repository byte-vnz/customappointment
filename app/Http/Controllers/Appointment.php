<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rollswan\CentralizedAttachment\Models\Attachment;
use Rollswan\CentralizedAttachment\Traits\WithAttachments;
use Com\Tecnick\Barcode\Barcode;
use Carbon\Carbon;
use App\Http\Requests\AppointmentReq as ValidateRequest;
use App\Helpers\StringHelper;
use App\Helpers\SettingHelper;
// use Illuminate\Support\Facades\Mail;
use App\Models\AppointmentTable;
use App\Models\TimeInterval;
use App\Models\Department;
use App\Models\Setting;
use App\Models\TransactionType;
use App\Models\BinSchedule;
use App\Models\Holiday;
use App\Http\Controllers\Glabs;
use App\Models\SmsOutbound;
use App\Models\SmsDefaultMsg;
use App\Http\Controllers\m360_Api;

use App\Notifications\AppointSchedReserve;
use Notification;
use DB;

class Appointment extends Controller
{
    use WithAttachments;

    private $max_count = 42;

    private $_model_appoint;

    private $_model_time_intervals;

    private $appointmentKey = '';
    private $reserveSlots = 0;

    public function __construct(AppointmentTable $apt_model, TimeInterval $interval)
    {
        $this->_model_appoint = $apt_model;

        $this->_model_time_intervals = $interval;
    }


    public function index()
    {
        $alerts = [
            24 => [
                'content' => "<h4><b>REMINDER:</b></h4> This appointment registration is strictly for PWDs and Senior Citizens of Barangay <brgy name>. Those who are not part of these groups are advised to wait for further announcements regarding the HELP Card registration.",
               // 'content' => "<b>Advisory:</b> At the end of the registration process, a QR code will be generated. Please take a screenshot of this QR code, as it will serve as your proof of appointment when you arrive on your scheduled date and time.",
            ],
        ];

        return view('index-content', ['time_intervals' => $this->_model_time_intervals->where('status', 1)->get(), 'departments' => Department::where('status', 1)->get(),'alerts' => $alerts ,'transtype' => TransactionType::where('id', 13)->get()]);
    }

    public function save(ValidateRequest $req)
    {
        $requestSchedDate = Carbon::createFromFormat('Y-m-d', $req->get('adate'));

      //  Check friday cut off
         if ($requestSchedDate->isFriday()) {
             if (!$this->checkFridayCutOffSettings($req)) {
                 $response_m = [
                     'error' => true,
                     // 'message' => "<b>Sorry,</b> the Business Permits and Licensing Office will undergo a scheduled decontamination on your selected date of appointment.",
                      'message' => "<b>Sorry,</b> the Business Permits and Licensing Office will be close on your selected date of appointment.",
                     'slot_message' => "",
                 ];

                 return response()->json($response_m);
             }
         }

               //  Check  cut off
         if ($req->departmentid == 24 && $req->transaction_type_id==20) {
             if (!$this->checkCutOffSettings($req)) {
                 $response_m = [
                     'error' => true,
                     
                      'message' => "<b>Sorry,</b> Registraion is already close.",
                     'slot_message' => "",
                 ];

                 return response()->json($response_m);
             }
         }


            if ($req->departmentid == 24) {
             if (!$this->mondayCutOffSettings($req)) {
                 $response_m = [
                     'error' => true,
                     
                      'message' => "<b>Sorry,</b> the date and time you selected for the Appointment is not available.",
                     'slot_message' => "",
                 ];

                 return response()->json($response_m);
             }
         }



        // Check weekends allowed appointments
        if ($requestSchedDate->isSaturday() || $requestSchedDate->isSunday()) {
            if (!$this->checkWeekendSettings($req, $requestSchedDate->dayOfWeek)) {
                $response_m = [
                    'error' => true,
                    'message' => "<b>Sorry,</b> the date and time you selected for the Appointment is not available.",
                    'slot_message' => "",
                ];

                return response()->json($response_m);
            }

            if ($req->departmentid == 23 && $requestSchedDate->isSunday()) {
           
                $response_m = [
                    'error' => true,
                    'message' => "<b>Sorry, </b> the office is close to the date and time you selected for the Appointment is not available.",
                    'slot_message' => "",
                ];

                return response()->json($response_m);
           
			}
        }

        // Check if holiday
        if ($this->isHoliday($req->adate)) {

            
            $holidayname = Holiday::whereHoliday($req->adate)->first();

            $response_m = [
                'error' => true,
                'message' => "<b>Sorry,</b> the date you selected for the Appointment is not available <br><br> <b>Reason:</b> $holidayname->name",
                'slot_message' => "",
            ];

            return response()->json($response_m);
        }

        // Check Available Slots For Business Permit (eid = 2), and Transaction Types ID ranges ONLY to 1 - 8
        if ($req->departmentid == 2 && $req->transaction_type_id >= 1 && $req->transaction_type_id <= 8) {
            // RENEWAL With Bin Filter
           
            if ($req->transaction_type_id == 1 && !$this->hasRenewalSettings($req)) {
                // Check if valid appointment
                if (!$this->isValidAppointment($req->bin, $req->adate)) {
                    $response_m = [
                        'error' => true,
                        'message' => "<b>Sorry,</b> the date you selected for the appointment does not fall on the scheduled extension for your BIN. <br>Allowed BIN ending for extension <br><br>
                        1-2 January 21 - January 26 <br>
                        3-4 January 27 - Feburary 2 <br>
                        5-6 Feburary 3 - Feburary 9 <br>
                        7-8 Feburary 10 - Feburary 16 <br>
                        9-0 Feburary 17 - Feburary 23 <br>",
                        'slot_message' => "",
						//2023 
                    ];

                    return response()->json($response_m);
                }

                // Get available slots
                $availableSlots = $this->getAvailableSlots($req);
            } 
            
            else {
                // Get available slots
                $availableSlots = $this->getBusinessPermitAvailableSlots($req);
            }
        } else if ($req->departmentid == 23 && $req->transaction_type_id == 12) {
           
            if ($req->transaction_type_id == 12 && !$this->hasRenewalSettings($req)){
            // Get available slots for ayala renewal
           
                if (!$this->isValidAppointment($req->bin, $req->adate)) {
                    $response_m = [
                        'error' => true,
                        'message' => "<b>Sorry,</b> the date you selected for the appointment does not fall on the scheduled extension for your BIN. <br>Allowed BIN ending for extension <br><br>
                        1-2 January 21 - January 26 <br>
                        3-4 January 27 - Feburary 2 <br>
                        5-6 Feburary 3 - Feburary 9 <br>
                        7-8 Feburary 10 - Feburary 16 <br>
                        9-0 Feburary 17 - Feburary 23 <br>",
                        'slot_message' => "",
						
                    ];

                    return response()->json($response_m);
                } 

                // Get available slots
                $availableSlots = $this->getAyalaRenewalAvailableSlots($req);
                  
            } else{

                $availableSlots = $this->getAyalaRenewalAvailableSlots($req);
            }
                

        } else if ($req->departmentid == 23 && $req->transaction_type_id == 16) {
            // Get available slots for ayala new
          
            $availableSlots = $this->getAyalaNewAvailableSlots($req);

        } else if ($req->departmentid == 24 && $req->transaction_type_id == 20) {
            // Get available slots for HELPCARDSITE1 
          
            $availableSlots = $this->getHelpcardSite1AvailableSlots($req);
        } 
        
        else {
            // Check if valid appointment
            if (!$this->isValidAppointment($req->bin, $req->adate)) {
                $response_m = [
                    'error' => true,
                    'message' => "<b>Sorry,</b> the date you selected for the appointment does not fall on the scheduled extension for your BIN.",
                    'slot_message' => "",
                ];

                return response()->json($response_m);
            }

            // Get available slots
            $availableSlots = $this->getAvailableSlots($req);
        }

        // Get config owner uuid
        $ownerUuid = $this->getConfigOwnerUuid();
		//$timeslotidq = $this->_model_time_intervals->where('timeslot', $post['atime'])->first()->tid;
		
		//if($timeslotidq = 6 &&  $requestSchedDate = '2021-12-14'){
		//	$response_m = [
        //       'error' => true,
        //        'message' => "<b>Sorry.</b> timeslot",
        //        'slot_message' => "",
        //    ];

        //    return response()->json($response_m);
			
		//};

        // Check for available slots
        if ($availableSlots <= 0) {
            $response_m = [
                'error' => true,
                'message' => "<b>Sorry.</b> There are no available slots for the requested schedule.",
                'slot_message' => "",
            ];

            return response()->json($response_m);
        }

        $post = $req->all();

        //$slots = $this->_model_appoint->slotReserveCount($req->get('adate'), $req->get('atime'));

        // if ($slots == $this->max_count) {
        //     $response_m = [
        //         'error' => true,
        //         'message' => "<b>Sorry.</b> There is an existing appointment with the same details. You can email us at requests@bploparanaque.com for clarifications.",
        //         'slot_message' => "",
        //     ];
        // } else {
        $post['refno'] = $this->_model_appoint->genRefNo();
        $post['dtcreated'] = now();
        $post['timeslotid'] = $this->_model_time_intervals->where('timeslot', $post['atime'])->first()->tid;
        $post['isonline'] = 1;
        $post['userid'] = 0;
        $post['appstatus'] = 0;
        $post['settings_owner_model'] = 'App\Models\Setting';
        $post['owner_uuid'] = $ownerUuid;

        array_walk($post, function ($arr) {
            $arr = (!is_array($arr)) ? strtoupper($arr) : '';
        });
       
        $save = $this->_model_appoint->create($post);

        //$post['current_pos'] = ++$slots;
        $post['current_pos'] = ++$this->reserveSlots;

        if ($save) {
            $post['eid'] = $save->eid;

            // Generate QR Code
            $this->generateQRCode($save->eid);

            // $slots = $this->_model_appoint->slotReserveCount($req->get('adate'), $req->get('atime'));
            // $slots = ($this->max_count - $slots);
            $slots = $availableSlots - 1;

            //$mail_to = 'requests@bploparanaque.com';
            // $mail_to = $save->emailadd;
            //Notification::route('mail', $mail_to)->notify(new AppointSchedReserve($post));


            $office = Department::where('eid', $post['departmentid'])->first();
            $transtypes = TransactionType::where ('id', $post['transaction_type_id'])->first();
            $newDate = date("m-d-Y", strtotime($post['adate']));
           

       // sending sms


            $mobile = $req->cno;
            $sms_api = new m360_Api;


        
        if ($sms_api->isValidMobile($mobile)) {
            $data = [
                    'ref_no' => $post['refno'],
                    'adate' => $newDate,
                    'atime' => $post['atime'],
                    'trans' => $transtypes->name,
                    
            ];
            $sms_api->sendSms($data,$mobile);
          
            
        } else {
            logger('Invalid owner mobile');
            
        }

// end sms
             $qr_url = url('attachment/'.$save->eid);
            $response_m = [
                    'error' => false,
                    'ref_no' => $post['refno'],
                    'adate' => $newDate,
                    'atime' => $post['atime'],
                    'trans' => $transtypes->name,
                    // 'bus_name' => $post['business_name'],
                    // 'bin' => $post['bin'],
                    'eid' => $post['eid'],
                    'qr_url' => $qr_url,
                    //'message' => "<b>Submission successful!</b> Your preferred schedule has been sent, kindly wait for an email from our system for appointment confirmation.",
                    'message' => "<b>Submission successful!</b> Your preferred schedule has been sent and will be verified, kindly wait for an SMS message from our system.",
                    'slot_message' =>"$slots available slot(s).",
                ];
        } else {
            return $save;
        }
        //}

        return response()->json($response_m);
    }


    public function pullSlotFinder(Request $req)
    {
        $time = time();

        $vald_date = ($time > strtotime('12:01 am')) ? 'tomorrow' : 'today';

        $req->validate([
            //'adate' => ['required', 'date', 'after:'.$vald_date],
            'atime' => ['required', 'exists:timeslot,timeslot'],
        ], [], [
            //'adate' => 'date',
            'atime' => 'timeslot',
        ]);

        $slots = $this->_model_appoint->slotReserveCount($req->get('adate'), $req->get('atime'));

        if ($slots == $this->max_count) {
            $msg = "There's no available slot(s).";
        } else {
            $slots = ($this->max_count - $slots);
            $msg = "$slots available slot(s).";
        }

        return response()->json(['message' =>$msg]);
    }

    public function indexCancel()
    {
        return view('cancel-content');
    }

    public function indexdverify()
    {
        return view('veri-content');
    }

    public function indexslotview()
    {
        $q = (new AppointmentTable)->showCountToday();

        return view('slotviewer', ['count' => $q]);
    }

    public function appointInfo(Request $req)
    {
        /*
           $req->validate([
               'reference_code' => ['required', 'alpha_num', 'exists:appmstr,refno'],
           ]);
    */

        $info = $this->_model_appoint->select('lastname', 'firstname', 'middlename', 'adate', 'atime', 'iscancel', 'canceldt')->where('refno', $req->get('reference_code'))->orWhere('bin', $req->get('reference_code'))->orderBy('eid', 'DESC')->first();

        if ($info->iscancel == 1) {
            $response = ['error' => true, 'fullname' => '', 'message' => "The appointment for reference code " .$req->get('reference_code'). " has already been cancelled"];
        } else {
            $response = ['error' => false, 'fullname' => ucwords(strtolower($info->firstname.' '.$info->lastname)), 'message' => 'Do you want to cancel the appointment dated on <b>'.date('M. d, Y', strtotime($info->adate)).'</b> at <b>'.$info->atime.'</b> with a reference code of <b>'.$req->get('reference_code').'</b>?'];
        }

        return response()->json($response);
    }

    public function cancel(Request $req)
    {
        /*   $req->validate([
               'reference_code' => ['required', 'alpha_num', 'exists:appmstr,refno'],
           ]);
    */
        $info = $this->_model_appoint->select('iscancel', 'canceldt')->where('refno', $req->get('reference_code'))->orWhere('bin', $req->get('reference_code'))->orderBy('eid', 'DESC')->first();

        if ($info->iscancel == 1) {
            $response = ['error' => true, 'message' => "This appointment information has already been cancelled "];
        } else {
            $update = $this->_model_appoint->where('refno', $req->get('reference_code'))->orWhere('bin', $req->get('reference_code'))->update(['iscancel' => 1, 'canceldt' => now(),'appstatus' => 2 ]);

            if ($update) {
                $response = ['error' => false, 'message' => 'This appointment information is now cancelled.'];
            } else {
                $response = $update;
            }
        }

        return response()->json($response);
    }


    public function acceptappt(Request $req)
    {
        /*   $req->validate([
               'reference_code' => ['required', 'alpha_num', 'exists:appmstr,refno'],
           ]);
    */
        $info = $this->_model_appoint->select('appstatus')->where('refno', $req->get('reference_code'))->first();

        if ($info->appstatus == 4) {
            $response = ['error' => true, 'message' => "This appointment information has already been accepted "];
        } else {
            $update = $this->_model_appoint->where('refno', $req->get('reference_code'))->update(['appstatus' => 4]);

            if ($update) {
                $response = ['error' => false, 'message' => 'This appointment information is now accepted.'];
            } else {
                $response = $update;
            }
        }

        return response()->json($response);
    }


    /**
     * View Appointment QR Code
     *
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function viewQRCode($id)
    {
        $attachment = Attachment::where('owner_model', 'App\Models\AppointmentTable')
        ->where('owner_uuid', $id)
        ->first();

        // Stream attachment
        return $this->streamAttachment($attachment);
    }

    /**
     * Appointment Verification
     *
     * @param string $refno
     * @return \Illuminate\View\View
     */
    public function verification($refno)
    {
        // Get the appointment data
        $appointment = AppointmentTable::where('refno', $refno)->orWhere('bin', $refno)->orderBy('eid', 'DESC')-> first();


        if (!$appointment) {
            return view('verificationnf');
        }

        // Prepare the data for schedule color indicator
        list($startTime, $endTime) = explode(' - ', $appointment->atime);
        $startTime = $appointment->adate . ' ' . Carbon::createFromFormat('g:i A', $startTime)->format('H:i:s');
        $endTime = $appointment->adate . ' '. Carbon::createFromFormat('g:i A', $endTime)->format('H:i:s');
        $currentDateTime = Carbon::now();

        // Schedule Color Indicator
        $isExpire = false;
        if ($currentDateTime->lt(Carbon::parse($startTime))) {
            // Before Schedule
            $scheduleIndicator = 'bg-danger';
        } elseif ($currentDateTime->gt(Carbon::parse($endTime))) {
            // After/Expire Schedule
            $scheduleIndicator = 'bg-danger';
            $isExpire = true;
        } else {
            // On Schedule
            $scheduleIndicator = 'bg-success';
        }

        // Green for on time sched. Red for expired sched.
        //$scheduleIndicator = ($currentDateTime->gt(Carbon::parse($endTime))) ? 'bg-danger' : 'bg-success';

        // We're done
        return view('verification', compact('appointment', 'scheduleIndicator', 'isExpire'));
    }

    /**
     * Generate Appointment QR Code
     *
     * @param int $id
     * @return \Rollswan\CentralizedAttachment\Traits\storeAttachment
     */
    public function generateQRCode($id)
    {
        // Get appointment data
        $appointment  = AppointmentTable::find($id);

        // Generate QR Code
        $barcode = (new Barcode)->getBarcodeObj(
            'QRCODE,H',
            route('appointment.verification', ['refno' => $appointment->refno]),
            -2,
            -2,
            'black',
            array(-2, -2, -2, -2)
        )->setBackgroundColor('white');

        // Get barcode as PNG
        $qrCode = $barcode->getPngData();

        // Store Attachment
        return $this->storeAttachment(
            $qrCode,
            'appointment-qrcode',
            'App\Models\AppointmentTable',
            $id,
            true
        );
    }


    public function bizSearch(Request $req)
    {
        $req->validate([
            'bin' => ['required', 'numeric', 'digits:10'],
        ]);

        $info = $this->_model_appoint->bizInfo($req->get('bin'));

        if (!empty($info)) {
            $response = ['error' => false, 'message' => 'Found business name.', 'biz_name' => $info->bus_name];
        } else {
            $response = ['error' => true, 'message' => 'Unable to find business name.'];
        }

        return response()->json($response);
    }

    /**
     * Validate Appointment using Bin and Request Date in Config Bin Schedule
     *
     * @param string $binNumber
     * @param string $date
     * @return bool
     */
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
                    $this->appointmentKey = $noBinFilter->key;
                    return true;
                }
            }

            return false;
        }

        // Set appointment key
        $this->appointmentKey = $binSchedule->key;

        // We're done
        return true;
    }

    /**
     * Get available slots
     *
     * @param Request $request
     * @return int
     */
    private function getAvailableSlots($request)
    {
        if ($this->appointmentKey == Setting::KEYS['online']) {
            // Prepare the data
            // $configWalkInPrioritySlots = SettingHelper::get(Setting::KEYS['walk_in_prio']);
            $configOnlineSlots = SettingHelper::get(Setting::KEYS['online']);
            // $reserveWalkInPrioritySlots = AppointmentTable::wherePrioritySettings()
            //     ->whereDate('adate', $request->get('adate'))
            //     ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
            //     ->count();
            $reserveOnlineSlots = AppointmentTable::whereOnlineSettings()
                ->whereDate('adate', $request->get('adate'))
                ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                ->count();

            // Set reserve slots
            // $this->reserveSlots = (int)$reserveWalkInPrioritySlots + (int)$reserveOnlineSlots;
            $this->reserveSlots = (int)$reserveOnlineSlots;

            // Compute Available Slots
            // $availableSlots = ((int)$configWalkInPrioritySlots + (int)$configOnlineSlots) - $this->reserveSlots;
            $availableSlots = (int)$configOnlineSlots - $this->reserveSlots;
        }

        if ($this->appointmentKey == Setting::KEYS['no_bin_filter']) {
            // Prepare the data
            $configNoBinFilterSlots = SettingHelper::get(Setting::KEYS['no_bin_filter']);
            $reserveNoBinFilterSlots = AppointmentTable::whereNoBinFilterSettings()
               ->whereDate('adate', $request->get('adate'))
               ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
               ->count();

            // Set reserve slots
            $this->reserveSlots = (int)$reserveNoBinFilterSlots;

            // Compute Available Slots
            $availableSlots = (int)$configNoBinFilterSlots - $this->reserveSlots;
        }

        // We're done
        return $availableSlots;
    }


    /**
     * Get ayala available slots for transaction renewal
     *
     * @param Request $request
     * @return int
     */
    private function getAyalaRenewalAvailableSlots($request)
    {
   
        // RENEWAL For No Bin Filter
        $configSlots = SettingHelper::get(Setting::KEYS['ayala_online_renewal']);
        $reserveSlots = AppointmentTable::whereAyalaOnlineRenewalSettings()
            ->whereDate('adate', $request->get('adate'))
            ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
            ->count();

        // Set reserve slots
        $this->reserveSlots = (int)$reserveSlots;

        // Set appointment key
        $this->appointmentKey = Setting::KEYS['ayala_online_renewal'];

        // Compute available slots
        $availableSlots = (int)$configSlots - $this->reserveSlots;

        return $availableSlots;
    }

       /**
     * Get new ayala available slots for transaction new
     *
     * @param Request $request
     * @return int
     */
    private function getAyalaNewAvailableSlots($request)
    {
   
        // New  Ayala
        $configSlots = SettingHelper::get(Setting::KEYS['ayala_online_new']);
        $reserveSlots = AppointmentTable::whereAyalaOnlineNewSettings()
            ->whereDate('adate', $request->get('adate'))
            ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
            ->count();

        // Set reserve slots
        $this->reserveSlots = (int)$reserveSlots;

        // Set appointment key
        $this->appointmentKey = Setting::KEYS['ayala_online_new'];

        // Compute available slots
        $availableSlots = (int)$configSlots - $this->reserveSlots;

        return $availableSlots;
    }


    private function getHelpcardSite1AvailableSlots($request)
    {
   
        // RENEWAL For No Bin Filter
        $configSlots = SettingHelper::get(Setting::KEYS['helpcard_online_site1']);
        $reserveSlots = AppointmentTable::whereHelpcardOnlineSettings()
            ->whereDate('adate', $request->get('adate'))
            ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
            ->count();

        // Set reserve slots
        $this->reserveSlots = (int)$reserveSlots;

        // Set appointment key
        $this->appointmentKey = Setting::KEYS['helpcard_online_site1'];

        // Compute available slots
        $availableSlots = (int)$configSlots - $this->reserveSlots;

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
     * Get business permit available slots for transaction types ranges 1 - 7
     *
     *  @param Request $request
     * @return int
     */
  
     private function getBusinessPermitAvailableSlots($request)
    {
        switch ($request->transaction_type_id) {
            case '1':
                // RENEWAL For No Bin Filter
                $configSlots = SettingHelper::get(Setting::KEYS['online_renewal']);
                $reserveSlots = AppointmentTable::whereOnlineRenewalSettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();

                // Set reserve slots
                $this->reserveSlots = (int)$reserveSlots;

                // Set appointment key
                $this->appointmentKey = Setting::KEYS['online_renewal'];

                // Compute available slots
                $availableSlots = (int)$configSlots - $this->reserveSlots;
                break;
            case '2':
                // AMENDMENT
                $configSlots = SettingHelper::get(Setting::KEYS['online_amendment']);
                $reserveSlots = AppointmentTable::whereOnlineAmendmentSettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();

                // Set reserve slots
                $this->reserveSlots = (int)$reserveSlots;

                // Set appointment key
                $this->appointmentKey = Setting::KEYS['online_amendment'];

                // Compute available slots
                $availableSlots = (int)$configSlots - $this->reserveSlots;
                break;
            case '3':
                // RETIREMENT
                $configSlots = SettingHelper::get(Setting::KEYS['online_retirement']);
                $reserveSlots = AppointmentTable::whereOnlineRetirementSettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();

                // Set reserve slots
                $this->reserveSlots = (int)$reserveSlots;

                // Set appointment key
                $this->appointmentKey = Setting::KEYS['online_retirement'];

                // Compute available slots
                $availableSlots = (int)$configSlots - $this->reserveSlots;
                break;
            case '4':
                // CERTIFICATION
                $configSlots = SettingHelper::get(Setting::KEYS['online_certification']);
                $reserveSlots = AppointmentTable::whereOnlineCertificationSettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();

                // Set reserve slots
                $this->reserveSlots = (int)$reserveSlots;

                // Set appointment key
                $this->appointmentKey = Setting::KEYS['online_certification'];

                // Compute available slots
                $availableSlots = (int)$configSlots - $this->reserveSlots;
                break;
            case '5':
                // MAYOR'S CLEARANCE
                $configSlots = SettingHelper::get(Setting::KEYS['online_mayors_clearance']);
                $reserveSlots = AppointmentTable::whereOnlineMayorsClearanceSettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();

                // Set reserve slots
                $this->reserveSlots = (int)$reserveSlots;

                // Set appointment key
                $this->appointmentKey = Setting::KEYS['online_mayors_clearance'];

                // Compute available slots
                $availableSlots = (int)$configSlots - $this->reserveSlots;
                break;
            case '6':
                // CLAIM STUB
                $configSlots = SettingHelper::get(Setting::KEYS['online_claim_stub']);
                $reserveSlots = AppointmentTable::whereOnlineClaimStubSettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();

                // Set reserve slots
                $this->reserveSlots = (int)$reserveSlots;

                // Set appointment key
                $this->appointmentKey = Setting::KEYS['online_claim_stub'];

                // Compute available slots
                $availableSlots = (int)$configSlots - $this->reserveSlots;
                break;
            case '7':
                // DROP BOX
                $configSlots = SettingHelper::get(Setting::KEYS['online_drop_box']);
                $reserveSlots = AppointmentTable::whereOnlineDropBoxSettings()
                    ->whereDate('adate', $request->get('adate'))
                    ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                    ->count();

                // Set reserve slots
                $this->reserveSlots = (int)$reserveSlots;

                // Set appointment key
                $this->appointmentKey = Setting::KEYS['online_drop_box'];

                // Compute available slots
                $availableSlots = (int)$configSlots - $this->reserveSlots;
                break;
                
                case '8':
                    // NEW
                    $configSlots = SettingHelper::get(Setting::KEYS['online_new']);
                    $reserveSlots = AppointmentTable::whereOnlineNewSettings()
                        ->whereDate('adate', $request->get('adate'))
                        ->where(['atime' => $request->get('atime'), 'iscancel' => 0])
                        ->count();
    
                    // Set reserve slots
                    $this->reserveSlots = (int)$reserveSlots;
    
                    // Set appointment key
                    $this->appointmentKey = Setting::KEYS['online_new'];
    
                    // Compute available slots
                    $availableSlots = (int)$configSlots - $this->reserveSlots;
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

        private function checkCutOffSettings($request)
    {
        // Prepare the data
        $requestSchedDate = Carbon::createFromFormat('Y-m-d', $request->get('adate'));
        list($startTime, $endTime) = explode(' - ', $request->get('atime'));
        $startTime = $requestSchedDate->format('Y-m-d') . ' ' . Carbon::createFromFormat('g:i A', $startTime)->format('H:i:s');
        $endTime = $requestSchedDate->format('Y-m-d') . ' '. Carbon::createFromFormat('g:i A', $endTime)->format('H:i:s');
        $CufOff = Carbon::parse($requestSchedDate->format('Y-m-d') . ' 23:00:00');

         // ✅ If month >= 9 and day >= 19, block (Sept 19 onwards)
    if (
        $requestSchedDate->month > 9 || 
        ($requestSchedDate->month == 9 && $requestSchedDate->day >= 19)
    ) {
        return false;
    }

        if (Carbon::parse($endTime)->gt($CufOff)) {
            return false;
        }

        // We're done
        return true;
    }

private function mondayCutOffSettings($request)
{
    // Prepare the data
    $requestSchedDate = Carbon::createFromFormat('Y-m-d', $request->get('adate'));
    list($startTime, $endTime) = explode(' - ', $request->get('atime'));

    $startTime = Carbon::parse($requestSchedDate->format('Y-m-d') . ' ' . Carbon::createFromFormat('g:i A', trim($startTime))->format('H:i:s'));
    $endTime   = Carbon::parse($requestSchedDate->format('Y-m-d') . ' ' . Carbon::createFromFormat('g:i A', trim($endTime))->format('H:i:s'));

    // Cutoff is Sept 22, 2025 1:00 PM
    $cutOff = Carbon::parse('2025-09-22 14:00:00');

    // ❌ If date is September 22 and start/end >= 1 PM, block
    if ($requestSchedDate->isSameDay($cutOff) && ($startTime->gte($cutOff) || $endTime->gte($cutOff))) {
        return false;
    }

    // ✅ Otherwise allow
    return true;
}



    /**
     * Check for online renewal settings
     *
     * @param Request $request
     * @return bool
     */
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

    private function hasAyalaRenewalSettings($request)
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
}