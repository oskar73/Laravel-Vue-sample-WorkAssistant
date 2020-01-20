<div class="row">
        @if($selectedTemplate)
            <style>
                .tem-{{$selectedTemplate->id}}.bgimg {
                    background-image: url({{$selectedTemplate->getFirstMediaUrl('preview')}});
                }
                .tem-{{$selectedTemplate->id}}.bgimg.replace {
                    background-image: url({{$selectedTemplate->getFirstMediaUrl('preview', 'thumb')}});
                }
            </style>
            <div class="col-md-4 col-lg-3 mb-3">
                <div class="template-preview-user">
                    <div class="template_browser">•••</div>
                    <a href="#" class="preview_bg w-100 height-250 view_template d-block position-relative bgimg progressive replace tem-{{$selectedTemplate->id}} template_item_choose h-zoom-in"
                       data-id="{{$selectedTemplate->id}}"
                    >
                    </a>
                </div>
                <div class="text-center">
                    <label for="template_item_{{$selectedTemplate->id}}">
                        <input type="radio" name="template_item" id="template_item_{{$selectedTemplate->id}}"
                               value="{{$selectedTemplate->id}}"
                               data-id="{{$selectedTemplate->id}}"
                               data-header="{{$selectedTemplate->header_id}}"
                               data-footer="{{$selectedTemplate->footer_id}}"
                               {{--data-img="{{$selectedTemplate->getFirstMediaUrl('preview')}}"--}}
                               data-slug="{{$selectedTemplate->slug}}"
                               data-name="{{$selectedTemplate->name}}"
                        >
                        {{$selectedTemplate->name}}
                    </label>
                    (<a href="" class="hover-none text-dark template_item_choose" target="_blank" data-id="{{$selectedTemplate->id}}"> <i class="fa fa-external-link-alt"></i></a>)
                </div>
            </div>

          @endif

    @forelse($templates as $key=>$template)
        <style>
            .tem-{{$template->id}}.bgimg {
                background-image: url({{$template->getFirstMediaUrl('preview')}});
            }
            .tem-{{$template->id}}.bgimg.replace {
                background-image: url({{$template->getFirstMediaUrl('preview', 'thumb')}});
            }
        </style>

        <div class="col-md-4 col-lg-3 mb-3">
            <div class="template-preview-user">
                <div class="template_browser">•••</div>
                <a href="#" class="preview_bg w-100 height-250 view_template d-block position-relative bgimg progressive replace tem-{{$template->id}} template_item_choose h-zoom-in"
                   data-id="{{$template->id}}"
                >
                </a>
            </div>
            <div class="text-center">
                <label for="template_item_{{$template->id}}">
                    <input type="radio" name="template_item" id="template_item_{{$template->id}}"
                           value="{{$template->id}}"
                           data-id="{{$template->id}}"
                           data-header="{{$template->header_id}}"
                           data-footer="{{$template->footer_id}}"
                           data-img="{{$template->getFirstMediaUrl('preview')}}"
                           data-slug="{{$template->slug}}"
                           data-name="{{$template->name}}"
                    >
                    {{$template->name}}
                </label>
                (<a href="" class="hover-none text-dark template_item_choose" target="_blank" data-id="{{$template->id}}"> <i class="fa fa-external-link-alt"></i></a>)
            </div>
        </div>
    @empty
        <div class="col-12 text-center pt-5">
            No items
        </div>
    @endforelse

    <!-- Blank Template : Starts -->
{{--        <div class="col-md-4 col-lg-3 mb-3">--}}
{{--            <div class="template-preview-user">--}}
{{--                <div class="template_browser">•••</div>--}}
{{--                <a href="#" class="preview_bg w-100 height-250 view_template d-block position-relative bgimg progressive replace"--}}
{{--                   data-id="0"--}}
{{--                >--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="text-center">--}}
{{--                <label for="template_item_none">--}}
{{--                    <input type="radio" name="template_item" id="template_item_none"--}}
{{--                           value="0"--}}
{{--                           data-id="0"--}}
{{--                           data-header="0"--}}
{{--                           data-footer="0"--}}
{{--                           data-img=""--}}
{{--                           data-slug=""--}}
{{--                           data-name="Blank Template"--}}
{{--                    >--}}
{{--                    Blank Template--}}
{{--                </label>--}}
{{--            </div>--}}
{{--        </div>--}}
        <!-- Blank Template : Ends -->

    <div class="col-12">
        {{$templates->links()}}
    </div>
</div>
