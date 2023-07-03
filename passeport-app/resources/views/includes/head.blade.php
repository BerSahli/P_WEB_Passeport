<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Laravel') }}</title>

<!-- Fonts -->
<link rel="dns-prefetch" href="//fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

<!-- Styles -->
{{-- <link rel="stylesheet" type="text/css" href="{{ url('/css/style.css') }}" /> --}}

<!-- Scripts -->
@vite(['resources/js/app.js'])
{{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}