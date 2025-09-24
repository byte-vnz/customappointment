@extends('layout')
@section('content')
<style>
.binlbl {
    font-size 20px;
    font-weight-bold;
    text-left;

}

.binfnt {
    font-size 20px;
    font-weight-bold;
    text-left;

}

.buslbl {
    font-size 15px;
    font-weight-bold;
    text-left;

}
</style>

<div class="row align-items-center d-flex min-vh-100">
    <div class="col-md-12 col-12 mx-auto">
        <form class="needs-validation" id="master-form" novalidate>
            <div class="card shadow mb-5">
                <div class="{{ $scheduleIndicator }} card-header text-white text-center">
                    <h5 class=""><i class="fas fa-globe-asia mr-2"></i>{{ config('app.name', 'Appointment') }} Schedule
                    </h5>
                    <h6 class="card-subtitle mt-4">
                    </h6>
                </div>


                <div class="card-body">

                    <div class="row">

                        <div class="col-md-12">
                            <h6 class="card-text">REFERENCE NUMBER: </h6>

                            <input class="rc_lbl" type="text" id="reference_codes" name="reference_code"
                                value="{{ $appointment->refno }}" readonly>
                        </div>

                    </div>
                    <div class="row">



                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h6>FULL NAME:</h6>
                            <h5>{{ strtoupper($appointment->lastname) }} {{ strtoupper($appointment->firstname) }},
                                {{ strtoupper($appointment->middlename) }}</h5>
                        </div>
                        <div class="col-md-8">

                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="card-text">LOCATION: </h6>
                            <h5 class="card-title">{{ $appointment->transactionType->name }} </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="card-text">DATE: </h6>
                            <h5 class="card-title">{{ date('M. d, Y', strtotime($appointment->adate)) }} </h5>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="card-text">TIME: </h6>
                            <h5 class="card-title">{{ trim(explode('-', $appointment->atime)[0]) }}</h5>
                        </div>
                    </div>

                    <div class="card-footer text-muted mb-2">
                        @if($isExpire)
                        <div class="row text-center">
                            <div class="col-md-12 col-12 text-danger text-center">YOUR SCHEDULE IS ALREADY CANCELLED OR
                                EXPIRED</div>
                        </div>
                        @endif
                    </div>

                    <button type="button" class="btn btn-success" id="submit-accept">Accept</button>


                </div>


                <!--
                    <div class="card-body">
					<dl>
						<div class="row mt-4">
							<div class="col-md-3">
								<p class="binlbl">BIN: </p>
							</div>
							<div class="col-md-9">
								<p class="binfnt">{{ $appointment->bin }} </p>
							</div>

						</div>
					</dl>
                        <!--
						<dl class="row">
							<span> BIN: </span> {{ $appointment->bin }}
                           <!-- <dt class="col-md-6 col-4  binlbl ">BIN: </dt><dd class="col-md-6 col-8">{{ $appointment->bin }}</dd>

						</dl>

						<dl>

								<div class="row">
										<div class="col-md-12">
											<dt class="buslbl"> BUSINESS NAME </dt>
										</div>
								</div>

								<div class="row">
										<div class="col-md-12">
											<dd> {{ strtoupper($appointment->business_name) }} </dd>
										</div>
								</div>

                        </dl>




                        @if($isExpire)
                            <dl class="row mt-4">
                                <dd class="col-md-12 col-8 text-danger text-center">YOUR SCHEDULE IS ALREADY EXPIRED</dd>
                            </dl>
                        @endif
                    </div>
						-->
            </div>
        </form>
    </div>
</div>
@endsection