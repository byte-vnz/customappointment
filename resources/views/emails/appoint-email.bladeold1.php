@component('mail::message')
<p>Please review the following details for <b>{{ ucwords(strtolower("$lastname, $firstname")) }}</b>. Scheduled on <b>{{ date('M. d, Y', strtotime($adate)) }}</b> at <b>{{ $atime }}.</b></p>

@component('mail::table')
| First Name       | Middle Name       | Last Name       |
|:----------------:|:-----------------:|:---------------:|
| {{ $firstname }} | {{ $middlename }} | {{ $lastname }} |

| Reference No. | Email           |
|:------------:|:----------------:|
| {{ $refno }} | {{ $emailadd }} |

| Contact    | Business / Company |
|:----------:|:------------------:|
| <b>{{ $cno }}</b> | {{ $bussname }}    |

| Purpose of Appointment |
| ---------------------- |
| {{ $remarks }}         |

@endcomponent

@endcomponent
