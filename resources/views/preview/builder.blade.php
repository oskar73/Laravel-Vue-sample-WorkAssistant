@extends('layouts.previewApp')

@section('title', $page->name)

@section('style')
    <link href="{{s3_asset('vendors/contentbuilder/assets/minimalist-blocks/content.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .out_content {
            background-color: {{$data['back_color']?? '#fff'}};
        }

        #wholecontainer {
            margin: auto;
            max-width: @if($data['width']=='100%'){{'100%'}} @elseif($data['width']==null){{'1200px'}} @else {{$data['width'].'px'}} @endif;
        }
    </style>
    {!! $page->css??'' !!}
@endsection
@section('content')
    <div class="out_content overflow-hidden min-vh-100">
        <div id="wholecontainer">
            {!! $page->content??'' !!}
        </div>
    </div>
@endsection

@section('script')
    {!! $page->script !!}
@endsection

