@extends('layout')

@section('content')
<div class="card mt-4">
  <div class="card-header">
				<h5 class=""><i class="fas fa-globe-asia mr-2"></i>City Government of Parañaque Appointment Schedule Available Walk - in Slot</h5>			
  </div>
  <div class="card-body">
    
	<ul class="list-group">
	<h1>
	
			@foreach($count as $detail)
			
					  <li class="list-group-item d-flex justify-content-between align-items-center">
							{{ $detail->atime }}
					
							
						<span class="badge badge-primary badge-pill"> <strong>{{ $detail->Total   }}</strong></span>
					  </li>

				
				
				
			@endforeach

	</h1>
	</ul>

  </div>
</div>
<script>
	window.setTimeout(function () {
	window.location.reload();
	}, 5000);
</script>
@endsection