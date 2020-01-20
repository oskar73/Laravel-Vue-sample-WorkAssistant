<div class="palette_items">
    @forelse($palettes as $palette)
        <a href="{{route('user.color-palettes.edit', $palette->id)}}"
           class="d-inline-block float-left p-2 palette_item_div"
           data-id="{{$palette->id}}"
           data-name="{{$palette->name}}"
        >
            <div class="palette_item_svg">
                {!! $palette->getPreview() !!}
            </div>
            <p class="mb-0 mt-2 text-center">{{$palette->name}}</p>
        </a>
    @empty
        <div class="text-center mt-5">
            Empty
        </div>
    @endforelse
    <div class="clearfix"></div>
</div>
