<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Website Management | Bizinabox</title>

    <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}" />

    <meta name="keywords" content="Bizinabox, Preview, Template, Business, Website" />
    <meta name="description" content="Bizinabox, Preview, Template, Business, Website">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

    <script src="{{ s3_asset('vendors/tinycolor/tinycolor.min.js') }}"></script>

    {{
        Vite::useBuildDirectory('assets/resources/vite')
    }}

    @vite(['resources/sass/builder.scss', 'resources/js/section-builder/build/builder.js'])
</head>

<body>
@routes

<div id="app" class="bz-page bz-page-edit"></div>

<script>
  window.user = @json(auth()->user());
  window.user.roles = @json(auth()->user()->roles);
  window.config = {
    websiteId: "{{ $template->web_id ? $template->web_id : $template->id }}",
    userTemplateId: "{{ $template->web_id ? $template->id : null }}",
    template: @json($template),
    token: '{{ csrf_token() }}',
    appURL: '{{ config('app.url') }}',
    s3AssetBaseUrl: '{{ config('app.s3_asset_base_url') }}',
    appDomain: '{{ config('app.domain') }}',
    appEnv: '{{ config('app.env') }}',
    unsplashApiKey: '{{ option('unsplash_api_key') }}',
    baseUrl: '{{url('account')}}',
    basePath: '{{ $basePath ?? '' }}',
    isTemplate: false,
    gateway: @json(option('gateway', [])),
    auth: false,
    urls: {
      websitePreviewURL: '{{ route('user.website.preview', ['id' => $template->id]) }}',
      addPageUrl: '{{ route('user.website.addPage') }}',
      deletePage: '{{ route('user.website.deletePage') }}',
      activateModule: '{{ route('user.website.activateModule', ['id' => $template->id]) }}',
      savePalette: '{{ route('user.palettes.store', ['type' => 'advanced']) }}'
    }
  }
</script>
</body>

</html>
