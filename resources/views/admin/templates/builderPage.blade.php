@extends('layouts.previewApp')

@section('title', $page->name)
@section('style')
    <link href="{{s3_asset('vendors/contentbuilder/assets/minimalist-blocks/content.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{s3_asset('vendors/contentbuilder/contentbuilder/contentbuilder.css')}}" rel="stylesheet" type="text/css" />
    <style>.out_content{ background-color:{{$data['back_color']?? '#fff'}}; } #wholecontainer {margin:auto;max-width: @if($data['width']=='100%'){{'100%'}} @elseif($data['width']==null){{'1200px'}} @else {{$data['width'].'px'}} @endif; }</style>
    {!! $page->css !!}
@endsection
@section('content')
    <div class="out_content pt-100 min-vh-100">
        <div id="wholecontainer">
            @if(empty($page->content)==true||$page->type=='box')

                <div class="row clearfix">
                    <div class="column full">
                        <div class="spacer height-80"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" data-noedit=""><div class="spacer height-80"></div></div>
                </div>
                <div class="row clearfix">
                    <div class="column full">
                        <h2 class="size-32" style="text-align: center;">New Legal Policy</h2>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="column full">
                        <p class="size-16" style="text-align: center; letter-spacing: 3px;">( SOMETHING TO SHARE )</p>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="column full">
                        <div class="spacer height-80"></div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="column half">
                        <img src="{{s3_asset('vendors/contentbuilder/assets/minimalist-blocks/example.jpg')}}">
                    </div>
                    <div class="column half">
                        <p style="text-align: justify;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" data-noedit=""><div class="spacer height-80"></div></div>
                </div>
            @else
                {!! $page->content !!}
            @endif
        </div>
    </div>

    <div class="control-panel">
        <div>Control Panel</div><br>

        <form action="{{route('admin.template.page.editContent', ['id'=>$page->id, 'type'=>'builder'])}}" id="controlForm" method="post">
            @csrf

            <a href="{{route('admin.template.page.editContent', ['id'=>$page->id, 'type'=>'box'])}}" class="text-underline switch_a">Box Style &#x000BB;</a><br><br>
            <a href="{{route('admin.template.item.edit', $template->id)}}" class="btn-primary m-btn--square btn-sm">Back</a><br><br>
            <button type="submit" id="btnSave" class="btn-success m-btn--square btn-sm">SAVE</button><br><br>
            <div>
                <label>
                    <input type="checkbox" id="getcheckbox" name="fullWidth" onclick="switchWidth()" @if($page->css=='100%')checked @endif>
                    FullWidth
                </label>
            </div>
            <div class="max-area" style="display:{{$data['width']=='100%'? 'none': 'block'}}">
                <div>MaxWidth</div>
                <input type="number" id="max-width" name="maxWidth" class="form-control m-input m-input--square box_input"
                       value="@if($data['width']!='100%'&&$data['width']!=null){{$data['width']}}@else{{'1200'}}@endif"
                       onchange="changeWidth()" step="50">
            </div>
            <div class="color-area">
                <div>Background</div>
                <input type="text" id="back_color" name="back_color" autocomplete="off" class="form-control m-input m-input--square box_input"
                       value="{{$data['back_color']?? '#ffffff'}}">
            </div>
            <input type="hidden" name="type" value="builder"/>
            <input type="hidden" id="inpHtml" name="inpHtml"/>
        </form>
    </div>

@endsection
@section('script')
    <script src="{{s3_asset('vendors/jquery/jquery-ui.min.js')}}" type="text/javascript"></script>
    <script src="{{s3_asset('vendors/contentbuilder/contentbuilder/contentbuilder.min.js')}}" type="text/javascript"></script>
    <script src="{{s3_asset('vendors/contentbuilder/assets/minimalist-blocks/content.js')}}" type="text/javascript"></script>
    <script src="{{s3_asset('vendors/colorpicker/jqColorPicker.min.js')}}"></script>
    <script> var $path = "{{asset('/')}}", page_id = {{$page->id}}</script>
    <script src="{{asset('assets/js/admin/template/builderPage.js')}}"></script>
    {!! $page->script !!}
@endsection
