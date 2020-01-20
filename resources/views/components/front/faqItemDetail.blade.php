<ul class="breadcrumb">
    {!! $title !!}
</ul>
<div class="faqItem">
    <li><a href="#/{{$item->category->slug?? ''}}/{{$item->slug}}">{{$item->title}}</a></li>
</div>

<div class="description_area position-relative mt-5">
    {!! $item->description !!}
</div>

<x-front.gallery :item="$item" :order="$item->order"></x-front.gallery>
