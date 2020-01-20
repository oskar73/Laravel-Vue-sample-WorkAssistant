<div class="tw-max-w-[1440px] mx-auto tw-mt-5">
    <div class="tw-grid tw-grid-cols-4 tw-gap-2">
        @foreach(array_slice($templates, 0, 4) as $i=>$template)
            <div>
                @include('components.front.home-template-item')
            </div>
        @endforeach
    </div>

    <div class="tw-grid tw-grid-cols-4 tw-gap-2 tw-my-2">
        <div>
            <div class="tw-grid tw-grid-cols-1 tw-gap-2">
                @foreach(array_slice($templates, 4, 2) as $i=>$template)
                    <div>
                        @include('components.front.home-template-item')
                    </div>
                @endforeach
            </div>
        </div>
        <div class="tw-col-span-2">
            <a href="{{route('template.index')}}" class="tw-flex" style="height: 1px; min-height: 100%">
                <figure data-href="{{ option('templates.front.box.image') }}" class="tw-w-full tw-h-full progressive replace">
                    <img src="{{ option('templates.front.box.image-thumb') }}" alt="template center box" class="preview tw-h-full" />
                </figure>
            </a>
        </div>
        <div>
            <div class="tw-grid tw-grid-cols-1 tw-gap-2">
                @foreach(array_slice($templates, 6, 2) as $i=>$template)
                    <div>
                        @include('components.front.home-template-item')
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="tw-grid tw-grid-cols-4 tw-gap-2">
        @foreach(array_slice($templates, 8, 4) as $i=>$template)
            <div>
                @include('components.front.home-template-item')
            </div>
        @endforeach
    </div>
    <div class="template_preview_window"></div>
</div>