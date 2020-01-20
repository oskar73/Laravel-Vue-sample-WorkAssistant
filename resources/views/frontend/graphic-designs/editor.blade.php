<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">
<head>
    <title>Bizinabox | Graphic Design Builder - Edit logo</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{asset('assets/img/favicon.ico')}}" />
    {{
        Vite::useBuildDirectory('assets/resources/vite')
    }}
    @vite(['resources/js/svg-editor/sass/app.scss', 'resources/js/svg-editor/sass/_editor.scss', 'resources/js/svg-editor/editor.js'])

    <link rel="canonical" href="{{ config('app.url') }}/graphics/design/edit/{{ $designHash }}">
    {{--    <link rel="stylesheet" href="{{$fontUrl}}?v={{config('app.version')}}" type="text/css" />--}}
</head>
<body>

@routes
<div id="app" class="editor"></div>
<script type="text/javascript">
    @auth
       window.user = @json(auth()->user());
       window.user.roles = @json(auth()->user()->roles);
    @endauth
        window.unsplashApiKey = '{{ option('unsplash_api_key') }}'
        window.svgData = {!! json_encode([
            'hash' => $designHash ?? '',
            'graphics' => $graphics ?? [],
            'liveView' => $liveView ?? false
        ]) !!}
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Base64/1.1.0/base64.min.js"></script>
<script async type="text/javascript" src="{{ asset('assets/js/pace.min.js') }}"></script>

</body>
</html>



