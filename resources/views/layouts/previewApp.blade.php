<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Bizinabox</title>

    @if($template->favicon)
        <link rel="icon" href="{{$template->favicon}}" />
    @else
        <link rel="icon" href="{{asset('assets/img/favicon.ico')}}" />
    @endif

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @yield('seo')
    <link rel="stylesheet" href="{{ mix('assets/resources/css/app.css') }}" />

    {!! $template->css??'' !!}

    @yield('style')
</head>
<body>

    @yield('content')

    <h1 hidden>{{$template->name}} - {{$seo->title??''}}</h1>

    <a href="#" class="scroll-to-top" style="display: inline;">
        &#8593;
    </a>

    @routes

    @include('components.global.toast')

    <script src="{{s3_asset('vendors/jquery/jquery.min.js')}}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    @yield('script')

    {!! $template->script??'' !!}

    <script src="{{asset('assets/js/dev1/preview.js')}}" type="text/javascript"></script>
</body>

</html>
