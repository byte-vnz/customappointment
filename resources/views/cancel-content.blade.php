@extends('layout')

@section('content')
<div class="row align-items-center mt-5">
    <div class="col-md-8 col-11 mx-auto">
        <form class="needs-validation" id="cancel-form" novalidate>
            <div class="card shadow mb-5">
            <h5 class="card-header text-muted"><i class="fas fa-calendar-times mr-2"></i> {{ config('app.name', 'Appointment') }} Cancellation</h5>
               <div class="card-body">
                    <div class="alert d-none" id="alert-box-form" role="alert"></div>
                    
                    <div class="form-group">
                        <label for="reference_code">REFERENCE CODE</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="reference_code" name="reference_code" placeholder="">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="cancel-find-info">
                                    <i class="fas fa-search mr-2"></i>
                                    Search
                                    <span class="spinner-border spinner-border-sm ml-3 d-none" id="search-spiner" role="status" aria-hidden="true"></span>
                                    <span class="sr-only">Loading...</span>
                                </button>
                            </div>
                        </div>
                        <div class="invalid-feedback">Please provide a reference code.</div>
                        <small class="form-text text-muted">Input your reference code.</small>
                    </div>

                    <div class="alert d-none" id="info-box">
                        <h4>Hi <span class="info-fullname"></span>,</h4>
                        <br>
                        <p></p>
                        <hr>
                        <div class="text-right">
                            <button type="button" class="btn btn-sm btn-success" id="submit-cancel">
                                <i class="fas fa-calendar-week mr-2"></i>
                                Proceed Cancellation
                                <span class="spinner-border spinner-border-sm ml-3 d-none" id="submit-cancel-spiner" role="status" aria-hidden="true"></span>
                                <span class="sr-only">Loading...</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
@endsection