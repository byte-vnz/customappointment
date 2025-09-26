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
                <div class="card-header  text-center">
                    <h5 class=""><i class="fas fa-globe-asia mr-2"></i> City Government of Parañaque Online Appointment
                        Schedule</h5>

                </div>


                <div class="card-body">

                    <div class="row">

                        <div class="col-md-12 text-center">
                            <h3 class="card-text">THIS REFERENCE NUMBER <strong>HAS NO APPOINTMENT FOUND</strong></h3>
                            <p class="mt-3 text-muted">
                                This page will go back in <span id="counter">10</span> seconds... <br>
                                <a href="#" id="forceBack" class="btn btn-link mt-2">Click here if you don’t want to
                                    wait.</a>
                            </p>

                        </div>



                    </div>



                </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let seconds = 10;
    let counterEl = document.getElementById('counter');
    let forceBackBtn = document.getElementById('forceBack');

    // Countdown
    let countdown = setInterval(function() {
        seconds--;
        if (counterEl) counterEl.textContent = seconds;

        if (seconds <= 0) {
            clearInterval(countdown);
            goBackAndRefresh();
        }
    }, 1000);

    // Force back on click
    forceBackBtn.addEventListener("click", function(e) {
        e.preventDefault();
        goBackAndRefresh();
    });

    // Go back & refresh
    function goBackAndRefresh() {
        if (document.referrer) {
            window.location.href = document.referrer; // always reloads previous page
        } else {
            window.location.href = "{{ url('/') }}"; // fallback if no referrer
        }
    }
});
</script>
@endsection