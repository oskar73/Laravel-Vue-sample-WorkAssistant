@extends('layouts.previewApp')

@section('title', $page->name)
@section('style')
    <link href="{{s3_asset('vendors/contentbuilder/box/box.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{s3_asset('vendors/contentbuilder/assets/minimalist-blocks/content.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{s3_asset('vendors/contentbuilder/assets/scripts/simplelightbox/simplelightbox.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{s3_asset('vendors/contentbuilder/contentbuilder/contentbuilder.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{s3_asset('vendors/contentbuilder/contentbox/contentbox.css')}}" rel="stylesheet" type="text/css" />

    @if($page->mainCss!=null)
        {!! $page->mainCss !!}
    @endif
    @if($page->sectionCss!=null)
        {!! $page->sectionCss !!}
    @endif
    {!! $page->css !!}
@endsection
@section('content')
    <div class="is-wrapper">
        @if(empty($page->content)==true||$page->type=='builder')
            @include('components.global.defaultBox')
        @else
            {!! $page->content !!}
        @endif
    </div>
    <div class="control-panel">
        <div>Control Panel</div><br>

        <form action="{{route('admin.template.page.editContent', ['id'=>$page->id, 'type'=>'box'])}}" id="controlForm" method="post">
            @csrf

            <a href="{{route('admin.template.page.editContent', ['id'=>$page->id, 'type'=>'builder'])}}" class="text-underline switch_a">&#x000AB; Free Style </a><br><br>
            <a href="{{route('admin.template.item.edit', $template->id)}}" class="btn-primary m-btn--square btn-sm">Back</a><br><br>
            <button type="submit" id="btnSave" class="btn-success m-btn--square btn-sm">SAVE</button><br><br>

            <input type="hidden" name="type" value="box"/>
            <input type="hidden" id="sHTML" name="sHTML"/>
            <input type="hidden" id="sMainCss" name="sMainCss"/>
            <input type="hidden" id="sSectionCss" name="sSectionCss"/>
        </form>
    </div>
@endsection
@section('script')
    <script src="{{s3_asset('vendors/jquery/jquery-ui.min.js')}}" type="text/javascript"></script>
    <script src="{{s3_asset('vendors/contentbuilder/assets/scripts/simplelightbox/simple-lightbox.min.js')}}" type="text/javascript"></script>
    <script src="{{s3_asset('vendors/contentbuilder/contentbuilder/contentbuilder.min.js')}}" type="text/javascript"></script>
    <script src="{{s3_asset('vendors/contentbuilder/contentbox/contentbox.min.js')}}" type="text/javascript"></script>
    <script src="{{s3_asset('vendors/contentbuilder/assets/minimalist-blocks/content.js')}}" type="text/javascript"></script>
    <script> var $path = "{{asset('/')}}", page_id = {{$page->id}}</script>
    <script src="{{asset('assets/js/admin/template/boxPage.js')}}"></script>
    <script src="{{s3_asset('vendors/contentbuilder/box/box.js')}}" type="text/javascript"></script>
    {!! $page->script !!}
@endsection
