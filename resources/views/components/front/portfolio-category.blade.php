<div class="overflow-x-auto cat_scroll_container pt-3">
    <div class="col-12">
        <div class="row flex-nowrap">
            @foreach($categories as $category)
                <div class="col-md-2 col-sm-3 col-6">
                    <a href="{{route('portfolio.category', $category->slug)}}"
                       class="d-block logo_item_preview_container {{$selected == $category->id? 'active':''}}"
                       id="category_item_{{$category->id}}"
                       data-id="{{$category->id}}">
                        <div class="preview">
                            <div class="row no-gutters thumbnail">
                                <div class="col-12 position-relative">
                                    <div class="thumbnail_overlay">
                                        <div  class="img_area">
                                            <figure data-href="{{$category->getFirstMediaUrl("image")!=''?$category->getFirstMediaUrl("image"):asset("assets/img/default.jpg")}}" class="w-100 progressive replace img-bg-effect-container mb-0 z-0">
                                                <img src="{{$category->getFirstMediaUrl("image", "thumb")!=''?$category->getFirstMediaUrl("image", "thumb"):asset("assets/img/default.jpg")}}" alt="{{$category->name}}" class="preview"/>
                                            </figure>
                                        </div>
                                        <div class="edit-template-container overflow-hidden h-cursor">
                                            <div class="fs-12px hide-white-loading text-black p-3 line-height-14px cat_desc_text">
                                                {{$category->description}}
                                            </div>
                                        </div>
                                        <div class="cat_desc_area">
                                            <span class="fs-12px">{{$category->name}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
