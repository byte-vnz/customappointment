@extends('layout')
@section('content')
    <div class="row align-items-center mt-5">
        <div class="col-md-8 col-11 mx-auto">
            <form class="needs-validation" novalidate method="post" action="{{ route('appointment.walk-in.create') }}">
                @csrf
                <div class="card shadow mb-5">
                    <h5 class="card-header text-muted">City Government of Parañaque Walkin Appointment</h5>
                    <div class="card-body">

                     
                        <div class="alert d-none" id="alert-box-form" role="alert"></div>

                            <div class="alert "  role="alert">
                                <h4><b>REMINDER:</b></h4> Registration at Parañaque City Hall is strictly limited to employees of the City Government of Parañaque. Parañaque Citizens belonging to the PWD or Senior Citizen sector should select OSPAR 1 as their registration site. Those not included in these groups are advised to wait for further announcements regarding HELP Card registration.
                        </div>


							<h6 class="card-title text-center bg-info text-white p-1"><i class="fas fa-address-book"></i> TRANSACTION DETAILS</h6>
						    <div class="form-group">
                            <div class="row">

                                <div class="col-md-12">
                                    <select name="departmentid" id="departmentid" class="form-control">
                                        <option value="24" selected >HELP CARD</option>

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
									<div class="clearfix">
										<div class="spinner-border spinner-border-sm text-black-50 d-none" id="transaction_spinner" role="status" style="position: absolute; margin-top: 12px;">
											<span class="sr-only">Loading...</span>
										</div>
									</div>
                                    <select name="transaction_type_id" id="transaction_type_id" data-old="{{ old('transaction_type_id') }}" class="form-control">
                                    
                                        @foreach ($transactiontype as $tp)
                                            <option {{ old('transaction_type_id') == $tp->id ? 'selected' : '' }} value="{{ $tp->id }}">{{ $tp->name }}</option>
                                        @endforeach
                                    
                                    
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
									<div class="input-group">
                                        {{--
										<input class="form-control text-uppercase {{ !empty(old('departmentid')) && old('departmentid')  != 2 ? 'd-none' : '' }}" type="text" placeholder="BIN" id="bin" min="0" step="1"
											name="bin" onkeypress='return event.charCode >= 48 && event.charCode <= 57' minlength="1"  maxlength="10" value="{{ old('bin') }}">
										
                                            <div class="input-group-append">
											<button class="btn btn-secondary" type="button" id="bin-biz-name">
												Check BIN
												<div class="spinner-border spinner-border-sm text-white d-none" id="biz_info_find" role="status" style="margin-left: 5px;">
													<span class="sr-only">Loading...</span>
												</div>
											</button>
										</div>
                                        
										<div class="invalid-feedback">Please provide a bin.</div>
									</div>
                                    --}}
                                </div>
                            </div>
                            {{-- 
                            <div class="row">

                                <div class="col-md-12">
                                    <input class="form-control text-uppercase {{ !empty(old('departmentid')) && old('departmentid')  != 2 ? 'd-none' : '' }}" type="text" placeholder="BUSINESS NAME" id="business_name"
                                        name="business_name" value="{{ old('business_name') }}" readonly>
                                    <div class="invalid-feedback">Please provide a business name.</div>
                                </div>
                            </div>
                            --}}
                        </div>  

                        <h6 class="card-title text-center bg-info text-white p-1"><i class="fas fa-user-friends mr-2"></i> PERSONAL INFORMATION</h6>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="labelhld" for="lastname">LAST NAME</label>
                                    <input class="form-control text-uppercase" type="text" placeholder="" id="lastname"
                                        name="lastname" value="{{ old('lastname') }}" required>
                                    <div class="invalid-feedback">Please provide a last name.</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="labelhld" for="firstname">FIRST NAME</label>
                                    <input class="form-control text-uppercase" type="text" placeholder="" id="firstname"
                                        name="firstname" value="{{ old('firstname') }}" required>
                                    <div class="invalid-feedback">Please provide a first name.</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="labelhld" for="middlename">MIDDLE NAME</label>
                                    <input class="form-control text-uppercase" type="text" placeholder="" id="middlename"
                                    name="middlename" value="{{ old('middlename') }}">
                                    <div class="invalid-feedback">Please provide a middle name.</div>
                                </div>

								<div class="col-md-4">
                                    <label class="labelhld" for="cno">CONTACT NUMBER</label>
                                    <input class="form-control" type="number" placeholder="" id="cno" min="0" step="1"
                                        name="cno" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ old('cno') }}">
                                    <div class="invalid-feedback">Please provide a contact number.</div>
                                </div>
                            </div>
                        </div>


                        <h6 class="card-title text-center bg-info text-white p-1"><i class="fas fa-map-marked-alt mr-2"></i>ADDRESS</h6>
                    <div class="form-group">
                            <div class="row">
                                    <div class="col-md-3">
                                        <label class="labelhld" for="houseno">HOUSE NO</label>
                                        <input class="form-control text-uppercase" type="text"  id="houseno"
                                            name="houseno" >
                                    </div>
                                    <div class="col-md-3">
                                        <label class="labelhld" for="blockno">BLOCK NO</label>
                                        <input class="form-control text-uppercase" type="text" placeholder="" id="blockno"
                                        name="blockno" >
                                    </div>
                                    <div  class="col-md-3">
                                        <label class="labelhld" for="lotno">LOT NO</label>
                                        <input class="form-control text-uppercase" type="text" placeholder="" id="lotno"
                                            name="lotno" >
                                    </div>
                                    <div class="col-md-3">
                                    <label class="labelhld" for="unitno">UNIT NO</label>
                                    <input class="form-control text-uppercase" type="text" placeholder="" id="unitno"
                                        name="unitno" >
                                    </div>
                            </div>
                    </div>
                    <div class="form-group">                  
                            <div class="row">
                                     <div class="col-md-3">
                                        <label class="labelhld" for="floorno">FLOOR NO.</label>
                                        <input class="form-control text-uppercase" type="text" placeholder="" id="floorno"
                                            name="floorno" >
                                    </div>
                                    <div class="col-md-9">
                                        <label class="labelhld" for="addr1">BUILDING NAME / BUILDING NO. / ROOM NO.</label>
                                        <input class="form-control text-uppercase" type="text" placeholder="" id="addr1"
                                            name="addr1" >
                                    </div>
                            </div>
                    </div> 
                    <div class="form-group">                            
                            <div class="row">
                                     <div class="col-md-12">
                                        <label class="labelhld" for="addr2">STREET / AVENUE / ROAD / EXTENSION / BOULEVARD / CORNER</label>
                                        <input class="form-control text-uppercase" type="text" placeholder="" id="addr2"
                                            name="addr2" >
                                    </div>
                                   
                            </div>
                    </div>
                    <div class="form-group">

                             <div class="row">
                                     <div class="col-md-12">
                                        <label class="labelhld" for="addr3">SUBDIVISION</label>
                                        <input class="form-control text-uppercase" type="text" placeholder="" id="addr3"
                                            name="addr3" >
                                    </div>
                                   
                            </div>
                    </div>
                   
                   


                        <h6 class="card-title text-center bg-info text-white p-1">
                            <i class="far fa-calendar-check mr-2"></i> APPOINTMENT
                            <span class="spinner-border spinner-border-sm ml-2 d-none" id="appoint-spiner" role="status" aria-hidden="true"></span>
                            <span class="sr-only">Loading...</span>
                        </h6>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <div class="form-inline d-flex justify-content-md-center">
                                    <label for="adate" class="mr-4" id="adate-label">DATE</label>
                                    {{-- @php
                                        $time = time(); // strtotime('5 pm');

                                        if ($time > strtotime('4:01 pm')) {
                                            $min_dt = date('Y-m-d', strtotime('+2 days'));

                                        } else {
                                            $min_dt = date('Y-m-d', strtotime('tomorrow'));

                                        }
                                    @endphp --}}
                                    <div class="div-sm-100">
                                        <input class="form-control" type="text" id="walk_in_adate" name="adate"  value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"   required >
                                    <div class="invalid-feedback">Please provide a date.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-inline d-flex justify-content-md-center">
                                    <label for="atime" class="mr-4">TIMESLOT</label>
                                    <select class="form-control" name="atime" id="walk_in_atime"  required>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($timeIntervals as $time)
                                            @php
                                                // Prepare the data
                                                list($startTime, $endTime) = explode('-', $time->timeslot);
                                                $currentDateTime = \Carbon\Carbon::now();
                                                $startTime = $currentDateTime->format('Y-m-d') . ' ' . \Carbon\Carbon::createFromFormat('g:i A', trim($startTime))->format('H:i:s');
                                                $configGracePeriod = (int)\App\Helpers\SettingHelper::get(\App\Models\Setting::KEYS['walk_in_grace_period']);
                                                $gracePeriod = $i == 1 ? $configGracePeriod : $configGracePeriod - 1;
                                                $disableTimeslot = false;

                                                // After Grace Period
                                                if ($currentDateTime->gt(\Carbon\Carbon::parse($startTime)->addMinutes($gracePeriod))) {
                                                    $disableTimeslot = true;
                                                }

                                                $i++;
                                            @endphp
                                            <option {{ old('atime') == $time->timeslot ? 'selected' : '' }} {{  $disableTimeslot ? 'disabled' : '' }} value="{{ $time->timeslot }}">{{ trim(explode('-', $time->timeslot)[0]) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback text-center">Please provide a timeslot.</div>
                                    <div class="d-sm-block">
                                        <small class="text-muted" id="timeslot-avail" style="display:none;"></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success float-right btn-block shadow-sm">
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
    @include('modules.appointment.walk-in.qr-code')
@endsection
