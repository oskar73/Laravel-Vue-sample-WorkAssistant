@props(["active"=>0, "label"=>'', "id"=>'', "withHead"=>1])

<div class="m-portlet m-portlet--mobile tab_area {{$active==1? 'area-active':''}}" id="{{$id}}" @if(!$withHead) style="background: transparent;border: none" @endif>
    {{$slot}}
</div>
