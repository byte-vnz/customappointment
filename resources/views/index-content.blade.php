@extends('layout')



@section('content')


<div class="row align-items-center mt-5">
    <div class="col-md-8 col-11 mx-auto">
        <form class="needs-validation" id="master-form" novalidate>
            <div class="card shadow mb-5">
                <h5 class="card-header text-muted"><i class="fas fa-globe-asia"></i>
                    {{ config('app.name', 'Appointment') }}</h5>
                <div class="card-body">

                    <h6 class="card-title text-center bg-info text-white p-1"><i class="fas fa-address-book"></i>
                        ADVISORY</h6>

                    <div class="alert " id="alert" role="alert">
                        At the end of the registration process, a QR code will be generated. Please take a screenshot of
                        this QR code, as it will serve as your proof of appointment when you arrive on your scheduled
                        date and time.
                    </div>

                    <div class="alert d-none" id="alert-box-form" role="alert"></div>

                    <div class="alert alert-info department-alert department-alert-active d-none" data-alert-id="24">
                        <p class="mb-0">
                            @foreach ($transtype as $tp)
                        <h4><b>REMINDER:</b></h4> This appointment registration is strictly for PWDs, Solo Parents, and
                        Senior Citizens of Barangay {{ $tp->name }}. Those who are not part of these groups are advised
                        to wait for further announcements regarding the HELP Card registration.
                        @endforeach

                        </p>
                    </div>


                    <h6 class="card-title text-center bg-info text-white p-1"><i class="fas fa-address-book"></i>
                        TRANSACTION DETAILS</h6>


                    <div class="form-group">
                        @foreach ($alerts as $dept_id => $alert)

                        @endforeach

                        <div class="row">
                            {{-- @if(env('SHOW_BUSINESS_NAME'))
                            <div class="col-md-12">
                                <input class="form-control text-uppercase" type="text" placeholder="BUSINESS / COMPANY NAME"
                                    id="bussname" name="bussname" required>
                                <div class="invalid-feedback">Please provide a business / company name.</div>
                            </div>
                            @endif --}}
                            <div class="col-md-12">
                                <select name="departmentid" id="departmentid" class="form-control">
                                    <option value="24">HELP CARD</option>

                                </select>
                            </div>
                            <div class="col-md-12">
                                <div class="clearfix">
                                    <div class="spinner-border spinner-border-sm text-black-50 d-none"
                                        id="transaction_spinner" role="status"
                                        style="position: absolute; margin-top: 12px;">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>


                                <select name="transaction_type_id" id="transaction_type_id" class="form-control">
                                    @foreach ($transtype as $tp)
                                    <option value="{{$tp->id }}">{{ $tp->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                {{-- <textarea class="form-control text-uppercase" placeholder="REASON" id="remarks"
                                name="remarks" rows="3" required></textarea>
                                <div class="invalid-feedback">Please provide a reason.</div> --}}
                                {{--
                                <div class="input-group">
                                    <input class="form-control text-uppercase" type="text" placeholder="BIN" id="bin" min="0" 
                                        name="bin" onkeypress='return event.charCode >= 48 && event.charCode <= 57' minlength="1" maxlength="10">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary btn-sm"" type="button" id="bin-biz-name">
												Check BIN
                                            <div class="spinner-border spinner-border-sm text-white d-none" id="biz_info_find" role="status" style="margin-left: 5px;">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">Please provide a bin.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input class="form-control text-uppercase" type="text" placeholder="BUSINESS NAME" id="business_name"
                                    name="business_name" readonly >
                                <div class="invalid-feedback">Please click the CHECK BIN for verification.</div>
                            </div>
                        </div>
                    </div>
--}}

                                <h6 class="card-title text-center bg-info text-white p-1"><i
                                        class="fas fa-user-friends mr-2"></i> PERSONAL INFORMATION</h6>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="labelhld" for="lastname">LAST NAME</label>
                                            <input class="form-control text-uppercase" type="text" id="lastname"
                                                name="lastname" required>
                                            <div class="invalid-feedback">Please provide a last name.</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="labelhld" for="firstname">FIRST NAME</label>
                                            <input class="form-control text-uppercase" type="text" id="firstname"
                                                name="firstname" required>
                                            <div class="invalid-feedback">Please provide a first name.</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="labelhld" for="middlename">MIDDLE NAME</label>
                                            <input class="form-control text-uppercase" type="text" id="middlename"
                                                name="middlename">
                                            <div class="invalid-feedback">Please provide a middle name.</div>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="card-title text-center bg-info text-white p-1"><i
                                        class="fas fa-map-marked-alt mr-2"></i>ADDRESS</h6>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="labelhld" for="houseno">HOUSE NO</label>
                                            <input class="form-control text-uppercase" type="text" id="houseno"
                                                name="houseno">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="labelhld" for="blockno">BLOCK NO</label>
                                            <input class="form-control text-uppercase" type="text" placeholder=""
                                                id="blockno" name="blockno">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="labelhld" for="lotno">LOT NO</label>
                                            <input class="form-control text-uppercase" type="text" placeholder=""
                                                id="lotno" name="lotno">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="labelhld" for="unitno">UNIT NO</label>
                                            <input class="form-control text-uppercase" type="text" placeholder=""
                                                id="unitno" name="unitno">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="labelhld" for="floorno">FLOOR NO.</label>
                                            <input class="form-control text-uppercase" type="text" placeholder=""
                                                id="floorno" name="floorno">
                                        </div>
                                        <div class="col-md-9">
                                            <label class="labelhld" for="addr1">BUILDING NAME / BUILDING NO. / ROOM
                                                NO.</label>
                                            <input class="form-control text-uppercase" type="text" placeholder=""
                                                id="addr1" name="addr1">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="labelhld" for="addr2">STREET / AVENUE / ROAD / EXTENSION /
                                                BOULEVARD / CORNER</label>
                                            <input class="form-control text-uppercase" type="text" placeholder=""
                                                id="addr2" name="addr2">
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="labelhld" for="addr3">SUBDIVISION</label>
                                            <input class="form-control text-uppercase" type="text" placeholder=""
                                                id="addr3" name="addr3">
                                        </div>

                                    </div>
                                </div>


                                <h6 class="card-title text-center bg-info text-white p-1"><i
                                        class="fas fa-info mr-2"></i>OTHER INFORMATION</h6>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="labelhld" for="emailadd">EMAIL ADDRESS</label>
                                            <input class="form-control" type="email" placeholder="" id="emailadd"
                                                name="emailadd" required>
                                            <div class="invalid-feedback">Please provide a email address.</div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="labelhld" for="emailadd">CELLPHONE NUMBER</label>
                                            <input class="form-control " type="text" pattern="\d*" id="cno" name="cno"
                                                maxlength="11" placeholder="" required>
                                            <div class="invalid-feedback">Please provide a Mobile Number.</div>


                                        </div>
                                    </div>
                                </div>

                                <h6 class="card-title text-center bg-info text-white p-1">
                                    <i class="far fa-calendar-check mr-2"></i> APPOINTMENT
                                    <span class="spinner-border spinner-border-sm ml-2 d-none" id="appoint-spiner"
                                        role="status" aria-hidden="true"></span>
                                    <span class="sr-only">Loading...</span>
                                </h6>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-inline d-flex justify-content-md-center">
                                            <label for="adate" class="mr-4 labelhld" id="adate-label">DATE</label>
                                            @php




                                            $min_dt = date('Y-m-d');


                                            @endphp
                                            <div class="div-sm-100">

                                                <input class="form-control" type="date" id="adate" name="adate"
                                                    min="{{ $min_dt }}">
                                                <div class="invalid-feedback">Please provide a date.</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-inline d-flex justify-content-md-center">
                                            <label for="atime" class="mr-4 labelhld">TIMESLOT</label>
                                            <select class="form-control" name="atime" id="atime" required>
                                                @foreach ($time_intervals as $time)
                                                <option value="{{ $time->timeslot }}">
                                                    {{ trim(explode('-', $time->timeslot)[0]) }}</option>
                                                @endforeach
                                            </select>

                                            <span id="slot-remaining" class="ml-3 text-success font-weight-bold"></span>
                                            <div class="invalid-feedback text-center">Please provide a timeslot.</div>
                                            <div class="d-sm-block">
                                                <small class="text-muted" id="timeslot-avail"
                                                    style="display: none;"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" id="btn-submit-appointment"
                                                class="btn btn-success float-right btn-block shadow-sm">
                                                <i class="fas fa-paper-plane mr-2"></i>
                                                Submit
                                                <span class="spinner-border spinner-border-sm ml-3 d-none"
                                                    id="submit-spiner" role="status" aria-hidden="true"></span>
                                                <span class="sr-only">Loading...</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 text-md-center">
                                            <a href="{{ url('/cancellation') }}" class="active btn btn-outline-success"
                                                role="button" aria-pressed="true">
                                                If you want to cancel your existing appointment click here!!
                                            </a>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
        </form>
    </div>
</div>


<!-- modal details -->
<div class="modal fade" id="as-modal" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form>
                <div class="modal-header bg-success text-white">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h5 class="modal-title "> APPOINTMENT DETAILS</h5>
                        </div>
                    </div>

                </div>



                <div class="modal-body">
                    <div class="row-md-12">
                        <div class="col-md-12 text-center">
                            This is your onsite appointment schedule. Kindly secure the <strong>Reference
                                Number</strong> or <strong>QR Code</strong> and present this upon entry Thank you.
                        </div>
                    </div>



                    <div class="row">

                        <div class="col-12 text-center mt-5">
                            <img src="" id="modal-qr-img" width="150px" height="150px"> <br>
                            <strong>REFERENCE NUMBER </strong>
                            <h3 class="aptrefno text-success"></h3>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-12 text-center ">
                            <strong>LOCATION </strong>
                            <h5 class="apttrans"></h5>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-12 text-center ">
                            <strong>DATE </strong>
                            <h5 class="aptadate"></h5>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-12 text-center">
                            <strong>TIME </strong>
                            <h5 class="aptatime"></h5>
                        </div>
                    </div>


                    <div class="row mb-2 mt-2 ">
                        <div class="col-md-12 text-center">
                            <a href="{{ route('download', ['filename' => 'HELP-FORM-v2.3.pdf']) }}">Click Here to
                                download
                                your HELP Form</a>
                        </div>

                    </div>

                    <div class="row mt-1">
                        <div class="col-md-12">

                            <br>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <div class="row-md-4">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="remainingModal" tabindex="-1" role="dialog" aria-labelledby="remainingModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="remainingModalLabel">Remaining Slots</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ✅ Modal initialized correctly!
            </div>
        </div>
    </div>
</div>


<script>
document.getElementById("adate").addEventListener("change", function() {
    const date = new Date(this.value + "T00:00:00"); // safe parsing
    const day = date.getDay(); // Sunday = 0, Saturday = 6

    if (day === 0) { // only block Sundays
        alert("Sundays are not allowed. Please select another day.");
        this.value = ""; // reset the field
        this.focus();
    }
});
</script>


<script>
window.maxSettingValue = @json($maxSettingValue);
</script>






@endsection