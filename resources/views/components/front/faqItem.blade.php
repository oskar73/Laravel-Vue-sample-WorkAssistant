<ul class="breadcrumb">
    {!! $title !!}
</ul>
@if($category!==null)
    <a href="{{$category->getFirstMediaUrl('image')}}" class="progressive replace" style="height:300px !important; width:300px !important">
        <img src="{{$category->getFirstMediaUrl('image', 'thumb')}}"
             alt="{{$category->name}}"
             class=" preview"
        >
    </a>
    <div class="mb-4 white-space-pre-line">
        {{$category->description}}
    </div>
@endif
@forelse($items as $item)
    <div class="faqItem">
        <li class="clickable-item"><a href="#/{{$item->category->slug?? ''}}/{{$item->slug}}">{{$item->title}}</a></li>
    </div>
@empty
    <div class="text-center">
        <h3>No item..</h3>
    </div>
@endforelse
<div>
    {{ $items->links() }}
</div>
