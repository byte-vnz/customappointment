<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsOutbound extends Model
{
    protected $table = 'outbound_messages';

    protected $connection = 'sms_conn';

    protected $fillable = [
        'sms_id',
        'shortcode',
        'sender_number',
        'sender_message',
        'sender_message_seq',
        'sent_datetime',
        'sent_datetime_long',
        'sms_total_pending',
        'sms_in_batch',
        'isread',
    ];

}