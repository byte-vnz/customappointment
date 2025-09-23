@extends('layout')
@section('content')
<style>
	.binlbl{
		font-size 20px;
		font-weight-bold;
		text-left;

	}

	.binfnt{
		font-size 20px;
		font-weight-bold;
		text-left;

	}
	.buslbl{
		font-size 15px;
		font-weight-bold;
		text-left;

	}

</style>

    <div class="row align-items-center d-flex min-vh-100">
        <div class="col-md-12 col-12 mx-auto">
            <form class="needs-validation" id="master-form" novalidate>
                <div class="card shadow mb-5">
                    <div class="card-header  text-center">
                        <h5 class=""><i class="fas fa-globe-asia mr-2"></i> City Government of Parañaque Online Appointment Schedule</h5>

                    </div>


					  <div class="card-body">
					  
					  		<div class="row">

								<div class="col-md-12 text-center">
									<h3 class="card-text">THIS REFERENCE NUMBER OR BIN <strong >HAS NO APPOINTMENT FOUND</strong></h3>
									
								</div>




					 </div>


					
                </div>
            </form>
        </div>
    </div>
@endsection
