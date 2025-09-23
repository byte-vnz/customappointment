<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use App\Models\AppointmentTable;

class AppointmentReq extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $regx_alpha = '/^[a-zA-Z .]+$/';
        $regx_num = '/^\+?[0-9\ -]+$/';

        $time = time();

        $vald_date = ($time > strtotime('4:01 pm')) ? 'tomorrow' : 'today';

        return [
            'lastname' => ['required', 'string', 'max:100', 'regex:'.$regx_alpha],
            'middlename' => ['nullable', 'string', 'max:100', 'regex:'.$regx_alpha],
            'firstname' => ['required', 'string', 'max:100', 'regex:'.$regx_alpha],
            'emailadd' => ['required', 'string', 'email', 'max:255'],
            //'adate' => ['required', 'date', 'after:'.$vald_date],
            'atime' => ['required', 'exists:timeslot,timeslot'],
            'cno' => ['required','min:11','max:11', 'regex:'.$regx_num],
            // 'bussname' => ['required', 'string', 'max:255'],
            //'remarks' => ['required', 'string'],
            //'bin' => ['nullable', 'string'],
            //'business_name' => ['required', 'string'],
            //'transaction_type_id' => ['required', 'integer', 'exists:transaction_types,id'],
            //'departmentid' => ['required', 'integer', 'exists:department,eid']
        ];
    }



    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'lastname' => 'last name',
            'firstname' => 'first name',
            'middlename' => 'middle name',
            'emailadd' => 'email address',
           // 'adate' => 'date',
            'atime' => 'timeslot',
            'cno' => 'contact number',
            //'business_name' => 'Click the CHECK BIN to verify business name ',
            //'remarks' => 'reason',
            'transaction_type_id' => 'transaction type',
            'departmentid' => 'office'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($rv) {
            // if ($this->hasAppointment()) {
            //     $rv->errors()->add('atime', "An active appointment exists for this BIN or Business Name.");
            // }

            // if (!$this->isValidBin()) {
            //     $rv->errors()->add('bin', "Please provide a bin.");
            // }

            // if (!$this->isValidBusinessName()) {
            //     $rv->errors()->add('business_name', "Click the CHECK BIN to verify the Business Name.");
            // }
        });
    }


    // private function hasAppointment()
    // {
    //     $bin = $this->get('bin');
    //     $date = $this->get('adate');
    //     $transaction_id = $this->get('transaction_type_id');
    //     $currentDate = Carbon::now();

    //     // default WHERE
    //     $check_by = 'bin';
    //     $check_val = $bin;

    //     if ($transaction_id == 8) { // 8 = BPLO NEW
    //         $check_by = 'business_name';
    //         $check_val = $this->get('business_name');
    //     }

        
    //     if ($transaction_id == 16) { // 16 = AYALA NEW
    //         $check_by = 'business_name';
    //         $check_val = $this->get('business_name');
    //     }

    //     $existingAppointments = AppointmentTable::where([$check_by => $check_val, 'iscancel' => 0])
    //         ->whereDate('adate', $date)
    //         ->first();

    //     $proceedingAppointments = AppointmentTable::where([$check_by => $check_val, 'iscancel' => 0])
    //         ->whereDate('adate', '>', $currentDate)
    //         ->first();

    //     $expiredAppointments = AppointmentTable::where([$check_by => $check_val, 'iscancel' => 0])
    //         ->whereDate('adate', '<', $currentDate)
    //         ->first();

    //     if (!empty($existingAppointments) || !empty($expiredAppointments) || !empty($proceedingAppointments)) {
    //         return true;
    //     }

    //     return false;
    // }

    /**
     * Validate Bin
     *
     * @return bool
     */
    // private function isValidBin()
    // {
    //     // Prepare the data
    //     $department = $this->get('departmentid');
    //     $bin = $this->get('bin');
    //     $transaction_id = $this->get('transaction_type_id');

    //     if ($department == 23 && empty($bin) && $transaction_id == 16) {
    //         return true;
    //     }


    //     // Check if selected is not business permit
    //     if ($department != 2 && empty($bin)) {
    //         return false;

    //     } else if ($department == 2 && empty($bin) && $transaction_id == 8) {
    //         return true;
       
    //     } else{
    //         return true;

    //     }

        
    // }

    /**
    * Validate Business Name
    *
    * @return bool
    */
//     private function isValidBusinessName()
//     {
//         // Prepare the data
//         $department = $this->get('departmentid');
//         $businessName = $this->get('business_name');

//         // Check if selected is not business permit
//         if ($department != 2 && is_null($businessName)) {
//             return false;
//         } 
//         else if (is_null($businessName)) {
//             return false;
//         }

//         return true;
//     }
}
