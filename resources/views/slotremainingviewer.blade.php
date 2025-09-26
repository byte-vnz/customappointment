@extends('layout')

@section('content')
<div class="card mt-4">
    <div class="card-header">
        <h5 class=""><i class="fas fa-globe-asia mr-2"></i>City Government of Parañaque Scheduled Appointment</h5>
        @foreach($location as $loc)
        <h2>{{ $loc->name }}</h2>
        @endforeach

        <div class="form-group">
            <div class="row">
                <div class="col col-md-3">
                    <label for="adate"> SELECT DATE:</label>
                    <input type="date" name="sdate" id="sdate" value="{{ date('Y-m-d') }}">
                </div>

                <div class="col col-md-9">


                    <h5> Total Appointment:
                        <span id="grand-total" class="badge badge-primary badge-pill">
                            {{ $total ?? 0 }}
                        </span>
                    </h5>
                </div>

            </div>

        </div>





        <div class="form-group">
            <label for="reference_code">REFERENCE CODE</label>
            <div class="input-group">
                <input type="text" class="form-control" id="reference_codes" name="reference_codes" placeholder="">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="cancel-find-infos">
                        <i class="fas fa-search mr-2"></i>
                        Search
                        <span class="spinner-border spinner-border-sm ml-3 d-none" id="search-spiner" role="status"
                            aria-hidden="true"></span>
                        <span class="sr-only">Loading...</span>
                    </button>
                </div>
            </div>
            <div class="invalid-feedback">Please provide a reference code</div>
            <small class="form-text text-muted">Input your reference code</small>
        </div>

    </div>
    <div class="card-body">

        <ul class="list-group" id="slot-list">
            @foreach($count as $detail)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <h4> {{ trim(explode('-', $detail->atime)[0]) }} </h4>
                <span class="badge badge-primary badge-pill">
                    <h4><strong>{{ $detail->Total }}</strong></h4>
                </span>
            </li>
            @endforeach
        </ul>

    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="aptmodals">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
                <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection