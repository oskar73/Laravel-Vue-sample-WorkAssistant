<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Bizinabox</title>

    <link rel="icon" href="{{asset('assets/img/favicon.ico')}}" />

    <meta name="keywords" content="Bizinabox, Preview, Template, Business, Website" />
    <meta name="description" content="Bizinabox, Preview, Template, Business, Website">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{
        Vite::useBuildDirectory('assets/resources/vite')
    }}

    @vite(['resources/sass/builder.scss', 'resources/js/section-builder/build/builder.js'])

    @yield('style')
</head>
<body>

@yield('content')


@yield('script')

@routes

</body>

</html>
