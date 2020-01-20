@props(["active"=>0, "label"=>'', "id"=>'', "withHead"=>1])

<x-layout.portletFrame active="{{$active}}" id="{{$id}}" withHead="{{$withHead}}">
    @if ($withHead)
    <x-layout.portletHead label="{!! $label?? '' !!}" />
    @endif
    <div class="m-portlet__body">
        {{$slot}}
    </div>
</x-layout.portletFrame>
