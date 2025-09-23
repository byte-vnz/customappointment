<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use App\Models\AppointmentTable;

class CreateAppointmentWalkInRequest extends FormRequest
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
        // Prepare the data
        $regxAlpha = '/^[a-zA-Z .]+$/';
        $regxNum = '/^\+?[0-9\ -]+$/';

        return [
            'lastname' => ['required', 'string', 'min:2', 'max:100', 'regex:'.$regxAlpha],
            'middlename' => ['nullable', 'string', 'min:2', 'max:100', 'regex:'.$regxAlpha],
            'firstname' => ['required', 'string', 'min:2', 'max:100', 'regex:'.$regxAlpha],
            //'adate' => ['required', 'date'],
            'atime' => ['required', 'exists:timeslot,timeslot'],
            'cno' => ['required', 'regex:'.$regxNum],
            //'bin' => ['nullable', 'string'],
            //'business_name' => ['required', 'string'],
            'transaction_type_id' => ['required', 'integer', 'exists:transaction_types,id'],
            'departmentid' => ['required', 'integer', 'exists:department,eid']
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
           // 'adate' => 'date',
            'atime' => 'timeslot',
            'cno' => 'contact number',
            //'business_name' => 'business / company name',
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
            //     $rv->errors()->add('atime', "You're already have .");
            // }

            // if (!$this->isValidBin()) {
            //     $rv->errors()->add('bin', "Please provide a bin.");
            // }

            // if (!$this->isValidBusinessName()) {
            //     $rv->errors()->add('business_name', "Click the CHECK BIN to verify the Business Name.");
            // }
        });
    }

    /**
     * Check if has an appointment
     *
     * @return bool
     */
    // private function hasAppointment()
    // {
    //     $bin = $this->get('bin');
    //     $date = $this->get('adate');
    //     $transaction_id = $this->get('transaction_type_id');
    //     $currentDate = Carbon::now();

    //     // default WHERE
    //     $check_by = 'bin';
    //     $check_val = $bin;

    //     if ($transaction_id == 8) { // 8 = BPLO NEW NEW
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

    // /**
    //  * Validate Bin
    //  *
    //  * @return bool
    //  */
    // private function isValidBin()
    // {
    //     // Prepare the data
    //     $department = $this->get('departmentid');
    //     $bin = $this->get('bin');
    //     $transaction_id = $this->get('transaction_type_id');

    //     // Check if selected is not business permit
    //     if ($department == 23 && empty($bin) && $transaction_id == 16) {
    //         return true;
    //     }
        
    //     if ($department != 2 && empty($bin)) {
    //         return false;

    //     } else if ($department == 2 && empty($bin) && $transaction_id == 8) {
    //         return true;
       
    //     } else{
    //         return true;

    //     }

        
   

        //if ($department == 2 && empty($bin) && $transaction_id != 11) {
        //    return false;

       //}else if ($department == 2 && empty($bin) && $transaction_id == 8) {
        //    return true;
       
       // }



    //     return true;
    // }

    /**
    * Validate Business Name
    *
    * @return bool
    */
    // private function isValidBusinessName()
    // {
    //     // Prepare the data
    //     $department = $this->get('departmentid');
    //     $businessName = $this->get('business_name');

    //     // Check if selected is not business permit
    //     if ($department == 2 && empty($businessName)) {
    //         return false;
    //     }

    //     return true;
    // }
}
