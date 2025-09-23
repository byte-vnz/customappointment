<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class m360_Api extends Controller
{

    protected $sms_origin_code = 4752; // BPLO PRQ SMS App

    public function isValidMobile(String $num)
    {
        $mobile_pattern = '/^(09|\+639)\d{9}$/';

        return preg_match($mobile_pattern, $num);
    }

    /**
     * Send SMS after payment paid/success.
     *
     * @param Object $info    Biz info or checkout logs info; must include user_id & app_id
     * @param String $mobile    Full mobile number (11 digit). e.g: 09123456789.
     * @param String $amount    Formatted amount only. e.g: 10,000.00.
     * @param String $receipt_no    Assigned billing-no. e.g: 2021-01-123.
     * @param String|null $custom_message   When exists, overwrite default sms message.
     * @return Bollean Always true
     */
    public function sendSms(Array $info, String $mobile)
    {
        // $code = config('app.api.sms_default.origin_code');
        // $id = config('app.api.sms_default.id');
        // $secret = config('app.api.sms_default.secret');
        // $pass_phrase = config('app.api.sms_default.pass_phrase');

        $is_sent = 0;
        $atime = trim(explode('-', $info['atime'])[0]);
       // $atime = $info['atime'];
        $adate = $info['adate'];
        $trans = $info['trans'];
        $ref_no = $info['ref_no'];
        
        $sms_url = "https://api.m360.com.ph/v3/api/broadcast";

        // $sms_message = 'Hello! This is your One Time Pin code: '.$otp_code;

        // $user_info = User::where('id', $user_id)->first();

        // $sms_message = "Hello {$user_profile->first_name}!\n\nThis is your One Time Pin: {$otp_code} to validate your registration. Thank you.\n\nREF. #{$ref_id}";
            $sms_message =  "Your appointment for HELP Card registration is scheduled on $adate at $atime at $trans. Please arrive at least 15 minutes before your scheduled time. If you are unable to come on time, you will need to book a new appointment. Your reference number is $ref_no";
       // $sms_message = "Hello! We successfully received your payment for Transaction No: $receipt_no Amount: $amount Thank you!\n\n(THIS IS A SYSTEM GENERATED MESSAGE)";


        // $post_data = [
        //     'app_id' => $id,
        //     'app_secret' => $secret,
        //     'message' => $custom_message ?? $sms_message,
        //     'address' => $mobile,
        //     'passphrase' => $pass_phrase,
        // ];

        $post_data = [
            'app_key' => "imt2NebIhAgaa4Yv",
            'app_secret' => "uZnNfEQrdCOt5YZ2zRVi00JSiz3pF1iD",
            'msisdn' => $mobile,
            'content' => $sms_message,
            'shortcode_mask' => "PQUE ONLINE",
            // 'rcvd_transid' => "<optional|blank>",
            // 'is_intl' => "<optional|false>",
            // 'dcs' => "<optional|0",
        ];


        // logger($post_data);
        try {
            $request = Http::withHeaders(['Content-Type' => 'application/json'])->post($sms_url, $post_data);
    
            $response = $request->json();

            $is_sent = 1;

            // logger($response);
 
        } catch (\Throwable $th) {
            //throw $th;
            logger($th);
            
        }

        return true;
    }

}
