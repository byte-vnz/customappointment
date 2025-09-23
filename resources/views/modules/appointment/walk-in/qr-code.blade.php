<div class="modal fade" id="qr-code-modal" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                     <div class="row">
						<div class="col-12 text-center">
							 <h5 class="modal-title" >ONSITE WALK-IN APPOINTMENT</h5>
						</div> 
					</div>
			
                </div>
					
			

				
				<div class="modal-body">
					<div class="row-md-12">
						<div class="col-md-12 text-center">
							This is your onsite walk-in appointment scheduled <strong>TODAY</strong>. Kindly secure the <strong>QR Code</strong> or the <strong>Reference Number</strong> and present this upon entry Thank you.
							Non-transferable and valid only <strong>TODAY</strong>.
						</div>
					</div>
                    
					<div class="row">					
                        <div class="col-12 text-center mt-5">
                            <strong>REFERENCE NUMBER </strong> <h4 class="appointment-refno"></h4>
                        </div>						      
                    </div>
					
						<div class="row mt-1">			
							<div class="col-md-12 text-center ">
								<strong>DATE </strong> <h5 class="appointment-date"></h5>
							</div>
						</div>
						
						<div class="row mt-1">
							<div class="col-md-12 text-center">
								<strong>TIME </strong> <h5 class="appointment-time"></h5>
							</div>
						</div>
					
					
                    <div class="row mb-2 mt-2">
                        <div class="col-md-12" id="qr_code_container">
                            <img>
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
{{-- @extends('layout')
@section('content')
    <div id="qr_code_container">
        <img src="{{ route('appointment.attachment', ['id' => $appointment->eid]) }}" >

        <div class="row align-items-center mt-sm-3">
            <div class="col-md-4 col-12 mx-auto">
                <div class="card shadow mb-5">
                    <div class="card-body">
                        <strong>REFERENCE NUMBER: </strong> <h4> {{ $appointment->refno }} </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}
