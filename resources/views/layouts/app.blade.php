<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>  
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    <!-- Data Table -->
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet">
    <script src="{{ asset('js/datatable.min.js') }}" ></script>
    <script src="{{ asset('js/datatable.js') }}" ></script>

    <!-- Datetime Picker -->
    <script type="text/javascript" src="{{asset('plugins/datetimepicker/js/moment.min.js') }}" ></script>
    <script type="text/javascript" src="{{asset('plugins/datetimepicker/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <link rel="stylesheet" href="{{asset('plugins/datetimepicker/css/tempusdominus-bootstrap-4.min.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css') }} "/> --}}
    <link rel="stylesheet" href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }} "/>


</head>
<body>
    <div id="app">
        
        @guest
        
        @else
        @include('inc.navbar')
        @endguest
        
        <div class="container-fluid">
            <main class="py-4">
                @yield('content')
            </main>
        </div>
        @include('inc.footer') 
    </div>

</body>
</html>

