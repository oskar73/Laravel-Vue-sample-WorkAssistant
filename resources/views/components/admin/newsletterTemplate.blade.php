@if(count($items))
    <div class="tw-w-full tw-grid tw-grid-cols-2 md:tw-grid-cols-3 lg:tw-grid-cols-4 xl:tw-grid-cols-6 tw-gap-8">
        @foreach($items as $item)
            <div class="tw-flex tw-flex-col tw-bg-white tw-shadow-lg">
                <div class="tw-flex tw-justify-between tw-items-center tw-gap-2 tw-bg-gray-500 tw-p-4 tw-text-white">
                    <span class="tw-font-bold">{{$item->name}}</span>
                    <a href="#" class="rename_btn" data-slug="{{$item->slug}}" data-name="{{$item->name}}"><i
                                class="la la-edit"></i></a>
                </div>
                <div class="tw-p-4 tw-flex tw-flex-col tw-gap-2">
                    <a href="{{route('admin.newsletter.template.preview', ['slug' => $item->slug])}}"
                       target="_blank"
                       class="btn btn-info">Preview</a>
                    <a href="{{route('admin.newsletter.template.edit', ['slug' => $item->slug])}}"
                       class="btn btn-primary">Edit</a>
                    <a href="#"
                       data-slug="{{$item->slug}}"
                       class="btn btn-danger delete_btn">Delete</a>
                </div>
            </div>
        @endforeach
    </div>
@else
    <span>There are no templates created yet.</span>
@endif