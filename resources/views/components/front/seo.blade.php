<meta property="og:type" content="website"/>
@if($seo['title']??null)
    <meta name="title" content="{{$seo['title']}}" />
    <meta property="og:title" content="{{$seo['title']}}" />
@endif

@if($seo['keywords']??null)
    <meta name="keywords" content="{{$seo['keywords']}}" />
@endif

@if($seo['description']??null)
    <meta name="description" content="{{$seo['description']}}">
    <meta name="og:description" content="{{$seo['description']}}" />
@endif

@if($seo['image']??null)
    <meta name="og:image" content="{{ $seo['image'] }}">
@else
    <meta property="og:image" content="https://i.scdn.co/image/ab67616d0000b273e0b64c8be3c4e804abcb2696">
@endif
