@component('mail::message')


Greetings,<br>
<br>
We are acknowledging your request for appointment.<br>
<br>
Please review the following details for <b>{{ ucwords(strtolower("$lastname, $firstname")) }}
</b>. Scheduled on <b>{{ date('M. d, Y', strtotime($adate)) }}</b> at <b>{{ trim(explode('-', $atime)[0]) }}</b> and is listed as <b>{{ $current_pos }}</b> on this schedule. 
<br>
You should arrive 15 minutes before your scheduled time. Failure to arrive in your scheduled appointment, <b>you must book a new one.</b>
<br>
FIRST NAME: {{ $firstname }}<br>
MIDDLE NAME: {{ $middlename }}<br>
LAST NAME: {{ $lastname }}<br>
REFERENCE NO.: {{ $refno }}<br>
EMAIL: {{ $emailadd }}<br>
CONTACT: {{ $cno }}
<br>

<br>
Please prepare the necessary requirements for this transaction, show up on time and present this QR Code.

<b>{{ date('M. d, Y', strtotime($adate)) }}</b> : <b>{{ trim(explode('-', $atime)[0]) }}.</b> <br>
<img src="{{ route('appointment.attachment', ['id' => $eid]) }}" width="150px" height="150px"> <br>
REFERENCE NO.: {{ $refno }}<br>
<br>
Thanks!<br>
<br>
City Government of Parañaque Online Appointment

@endcomponent

