<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', $seo['title']) | Bizinabox</title>
    <link rel="icon" href="{{asset('assets/img/favicon.ico')}}" />

    <meta name="keywords" content="{{$seo['keywords']}}" />
    <meta name="description" content="{{$seo['description']}}">
    <meta name="og:image" content="{{$seo['image']}}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <!-- Google tag (gtag.js) bizinaboxllc@gmail.com -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-4P8EXQ4VJT"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-4P8EXQ4VJT');
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.2.96/css/materialdesignicons.min.css" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"  />
    <link rel="stylesheet" href="{{s3_asset('vendors/izitoastr/iziToast.min.css')}}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    {{
        Vite::useBuildDirectory('assets/resources/vite')
    }}

    @vite(['resources/sass/front.scss'])

    {!!$seo['head_code']!!}

    @yield('seo')

    @yield('style')

    @stack('style')

</head>
<body>
<div class="main-wrapper">
    <x-front.header></x-front.header>

    @yield('content')

    <x-front.feature></x-front.feature>

    <x-front.footer></x-front.footer>

    <a href="#" class="scroll-to-top"><i class="fas fa-angle-up" aria-hidden="true"></i></a>
</div>

<script src="{{s3_asset('vendors/izitoastr/iziToast.min.js')}}"></script>
<script src="{{asset('assets/js/dev2/core.min.js')}}"></script>
<script src="{{asset('assets/js/dev2/main.js')}}"></script>
<script src="{{asset('assets/js/dev2/progressive-image.js')}}"></script>
<script src="{{asset('assets/js/dev1/both.js')}}"></script>
<script src="{{asset('assets/js/dev1/front.js')}}"></script>

<x-global.toast></x-global.toast>

<script>
    var token = $('meta[name="csrf-token"]').attr('content');
</script>

@yield('script')

@stack('script')

{!!$seo['bottom_code']!!}

<x-front.livechat></x-front.livechat>
</body>

</html>
