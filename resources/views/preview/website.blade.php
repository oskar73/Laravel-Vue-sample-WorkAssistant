<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title> {{ $template->name  }} | Template Preview Bizinabox</title>

  @if($template->favicon)
    <link rel="icon" href="{{$template->favicon}}" />
  @else
    <link rel="icon" href="{{asset('assets/img/favicon.ico')}}" />
  @endif

  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <link rel="canonical" href="{{ config('app.url') }}/{{ $basePath }}">

  {!! $template->css??'' !!}
</head>
<body>

<div id="app" class="bz-page"></div>

@routes

@include('components.global.scroll-to-top')

<script>
    window.template = {!! $template !!}
    window.modules = []
    window.websiteId = "{{ $template->id }}"
    window.config = {
      websiteId: "{{ $template->id }}",
      template: @json($template),
      token: '{{ csrf_token() }}',
      appURL: '{{ config('app.url') }}',
      s3AssetBaseUrl: '{{ config('app.s3_asset_base_url') }}',
      appDomain: '{{ config('app.domain') }}',
      appEnv: '{{ config('app.env') }}',
      unsplashApiKey: '{{ option('unsplash_api_key') }}',
      isTemplate: true,
      baseUrl: '{{url('admin')}}',
      basePath: '{{ $basePath }}',
      urls: {
        storeUrl: '{{ route('admin.palettes.store', ['type' => 'advanced']) }}',
      }
    }
</script>

<link rel="stylesheet" href="{{asset('assets/resources/webpack/website.css')}}" crossorigin="anonymous" />
<script async src="{{asset('assets/resources/webpack/website.js')}}"></script>
{!! $template->script??'' !!}

</body>

</html>
