<ol class="breadcrumb">
	{!! $title !!}
</ol>
@if($category!==null)
	<a href="{{$category->getFirstMediaUrl('image')}}" class="w-100 progressive replace tw-max-w-xl tw-mx-auto">
		<img src="{{$category->getFirstMediaUrl('image', 'thumb')}}"
		     alt="{{$category->name}}"
		     class=" preview"
		>
	</a>
	<div class="mt-5 white-space-pre-line">
		{{$category->description}}
	</div>
@endif
<div class="tw-grid tw-grid-cols-fill-300 tw-mt-4 tw-gap-2.5">
	@forelse($templates as $key=>$template)
		<div class="tw-bg-white tw-rounded-sm tw-overflow-hidden tw-shadow">
			<div class="tw-absolute tw-z-[99] tw-p-2">
				@if($template->featured) <span class="tw-bg-[#ffffff99] tw-rounded-sm tw-p-1 tw-text-green-700 tw-shadow tw-ml-2.5"> Featured </span> @endif
				@if($template->new) <span class="tw-bg-[#ffffff99] tw-rounded-sm tw-p-1 tw-text-red-700 tw-shadow tw-ml-2.5"> New </span> @endif
			</div>
			<div class="aspect-view" style="--ratio: 1.5">
                <div>
                    <figure data-href="{{$template->getFirstMediaUrl("preview")}}" class="w-100 progressive replace m-0">
                        <img src="{{$template->getFirstMediaUrl("preview","thumb")}}" alt="{{$template->name}}" class="preview img-full" />
                    </figure>
                </div>
			</div>
			<div class="tw-p-4">
				<b>{{$template->name}}</b> <br/>
				<small class="tw-text-gray-700">By Bizinabox</small>
				<div class="tw-flex tw-items-center tw-justify-end">
					@if($type == 'website')
					<a href="javascript:void(0)" data-id="{{$template->id}}" class="btn btn-sm btn-outline-info mr-2 choose_temp_btn">Choose Template</a>
					@else
					<a href="javascript:void(0)" data-url="{{route('package.index', ['template' => $template->slug])}}" class="btn btn-sm btn-outline-info mr-2 choose_btn">Choose Template</a>
					@endif
                    <a href="javascript:void(0)" data-slug="{{$template->slug}}" class="template_item_choose btn btn-sm btn-outline-info">Preview</a>
				</div>
			</div>
		</div>
	@empty
		<div class="col-md-12 tw-text-center">
			<h3>No template..</h3>
		</div>
	@endforelse
</div>
<div class="tw-mt-4">
	{{ $templates->links() }}
</div>
