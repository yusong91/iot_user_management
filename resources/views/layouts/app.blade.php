<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title') - {{ setting('app_name') }}</title>
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ url('assets/img/icons/apple-touch-icon-144x144.png') }}" />
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ url('assets/img/icons/apple-touch-icon-152x152.png') }}" />
    <link rel="icon" type="image/png" href="{{ url('assets/img/icons/favicon-32x32.png') }}" sizes="32x32" />
    <link rel="icon" type="image/png" href="{{ url('assets/img/icons/favicon-16x16.png') }}" sizes="16x16" />
    <meta name="application-name" content="{{ setting('app_name') }}"/>
    <meta name="msapplication-TileColor" content="#FFFFFF" />
    <meta name="msapplication-TileImage" content="{{ url('assets/img/icons/mstile-144x144.png') }}" />

    
    <link media="all" type="text/css" rel="stylesheet" href="{{ url(mix('assets/css/vendor.css')) }}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ url(mix('assets/css/app.css')) }}">
    
<style>

    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
        box-shadow:  0px 0px 0px 0px #000;
    }

    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
    }

    .custom-width{
        padding: 0 !important;
        margin-bottom: 10px !important;
        margin-right: 10px !important;
        margin-left: 10px !important;        
    }

</style>
    
    @yield('styles')

    @hook('app:styles')
</head>
<body>
    @include('partials.navbar')

    <div class="container-fluid">
        <div class="row">
            @include('partials.sidebar.main')

            <div class="content-page">
                <main role="main" class="px-4">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <script src="{{ url(mix('assets/js/vendor.js')) }}"></script>
    <script src="{{ url('assets/js/as/app.js') }}"></script>
    <!-- JavaScript Bundle with Popper -->

    <script>
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        



    function myFunction(id) {
        
        $('#'+ id).datepicker({
            format: 'dd/mm/yyyy'
         });
    }

    

    </script>

    @yield('scripts')

    @hook('app:scripts')
</body>
</html>
