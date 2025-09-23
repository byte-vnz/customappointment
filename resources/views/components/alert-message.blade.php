@if (session()->has('success'))
    <script>
        $(function () {
            $('#alert-box-form').removeClass('alert-danger d-none').addClass('alert-success').html('{!! session()->get('success') !!}');
        });
    </script>
@endif
@if (session()->has('redirect'))
    <script>
        $(function () {
            // var popup = window.open('{{ session()->get('redirect') }}', "_blank");
            // popup.focus();
            // $(document).on('ready', function() {
                $.ajax({
                    url: '{{ session()->get('redirect') }}',
                    method: 'GET'
                }).done(function (data) {
                    $('#qr-code-modal img').attr('src', 'attachment/' + data.result.eid);
                    $('.appointment-refno').html(data.result.refno);
                    $('.appointment-date').html(data.result.adate);
                    $('.appointment-time').html(data.result.atime);
                    //console.log($('#qr-code-modal').length);
                    $('#qr-code-modal').modal('show');
                });
            // });

            
        });
    </script>
@endif
@if (session()->has('error'))
    <script>
        $(function () {
            $('#alert-box-form').removeClass('alert-success d-none').addClass('alert-danger').html('{!! session()->get('error') !!}');
            $(window).scrollTop(0);
        });
    </script>
@endif
@if ($errors->count() > 0)
    <script>
        $(function () {
            let err_list = '';
            @foreach ($errors->all() as $error)
                err_list += '<li>{{ $error }}</li>';
            @endforeach
            $('#alert-box-form').removeClass('alert-success d-none').addClass('alert-danger').append('<ul class="mb-0">' + err_list + '</ul>');
            $(window).scrollTop(0);
        });
    </script>
@endif
