<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;


class Glabs extends Controller
{

    protected $sms_origin_code = 2121; // PRQ SMS App


    public function sendSched(String $mobile, String $custom_message)
    {
        $code = config('app.api.sms_default.origin_code');
        $id = config('app.api.sms_default.id');
        $secret = config('app.api.sms_default.secret');
        $pass_phrase = config('app.api.sms_default.pass_phrase');

        $sms_url = "https://devapi.globelabs.com.ph/smsmessaging/v1/outbound/{$code}/requests";

        // $sms_message = 'Hello! This is your One Time Pin code: '.$otp_code;

        // $user_info = User::where('id', $user_id)->first();

        $post_data = [
            'app_id' => $id,
            'app_secret' => $secret,
            'message' => $custom_message,
            'address' => $mobile,
            'passphrase' => $pass_phrase,
        ];

        // logger($post_data);
        try {
            $request = Http::withHeaders(['Content-Type' => 'application/json'])->post($sms_url, $post_data);
    
            $response = $request->json();

        } catch (\Throwable $th) {
            //throw $th;
            logger($th);

            $response['error'] = true;
            
        }
        
        if (!empty($response['error'])) {
            $sent_otp = false;
            $message = 'Unable to send scheduling sms this time. Please try again later.';

            $response = [];
            
        } else {

            $sent_otp = true;
            $message = $post_data['message'];

        }

        return ['delivered' => $sent_otp, 'message' => $message, 'response_data' => $response];
    }


    public function verifyOtp(String $otp_code, String $ref_id)
    {
        $otp_check = OtpLog::where(['code' => $otp_code, 'ref_id' => $ref_id])->first();
        
        if (!empty($otp_check) && empty($otp_check->otp_done)) {
            $time_verified = now();
            
            $user_id = $otp_check->user_id;

            $otp_check->otp_done = 1;
            
            $otp_check->save();

            Beneficiaries::where('user_id', $user_id)->update(['contact_no_date_active' => now()]);

            User::where('id', $user_id)->update(['mobile_verified_at' => $time_verified]);

            return ['user_id' => $user_id, 'verified_at' => $time_verified];

        } else {
            return false;

        }

    }

    public function updateOtpRecord(String $ref_id, String $otp_code, Array $custom_update_fields = null)
    {
        $update_fields = $custom_update_fields ?? ['otp_sent_datetime' => now()];

        return OtpLog::where(['code' => $otp_code, 'ref_id' => $ref_id])->update($update_fields);
    }

}
