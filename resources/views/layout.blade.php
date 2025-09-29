<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta content="Parañaque City Online Business Appointment" name="description" />
    <meta content="Lloyd Ababao" name="author" />
    <meta content="Robinson Cusipag" name="author" />
    <meta content="Rollswan Acebedo" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">

    <link type="text/css" rel="stylesheet" href="{{ asset('css/app.css') }}" media="screen,projection" />

    <title>{{ config('app.name', 'Appointment') }}</title>

    <style>
    body {
        background: #76b852;
        background: -webkit-linear-gradient(to right, #76b852, #8dc26f);
        background: linear-gradient(to right, #76b852, #8dc26f);
    }
    </style>
</head>

<body>
    <main class="wrapper h-100">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>


    <script>
    var verificationUrl = "{{ url('/verification') }}";
    window.slotviewerUrl = "{{ route('slotviewer') }}";
    const acceptUrl = "{{ url('accept') }}";
    const slotViewerUrl = "{{ url('/slotviewer') }}";
    </script>
    <script>
    window.timeslotsUrl = "{{ route('timeslots.all') }}";
    window.remainingCountsUrl = "{{ route('remaining.counts') }}";
    </script>

    <script type="text/javascript" src=" {{ asset('js/app.js') }}?v={{ time() }}"></script>

    @include('components.alert-message')
</body>

</html>