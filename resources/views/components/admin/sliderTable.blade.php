<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable {{$selector}}">
        <thead>
        <tr>
            <th>
                Name
            </th>
            <th>
                Product type
            </th>
            <th>
                Product Name (or URL)
            </th>
            <th>
                Image
            </th>
            <th>
                Featured name
            </th>
            <th>
                Created At
            </th>
            <th class="no-sort">
                Action
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($sliders as $slider)
            <tr>
                <td>
                    {{$slider->name}}
                </td>
                <td>
                    @if($slider->model_type == 'url')
                        URL
                    @else
                        {{moduleName($slider->modelToSlug($slider->model_type))}}                    @endif
                </td>
                <td>
                    @if($slider->model_type == 'url')
                        {{$slider->model_id}}
                    @else
                        {{$slider->model->name}}
                    @endif
                </td>
                <td>
                    <a href="{{$slider->getFirstMediaUrl('image')}}" class="w-150px progressive replace m-auto">
                        <img src="{{$slider->getFirstMediaUrl('image', 'thumb')}}"
                             alt="{{$slider->name}}"
                             class="w-300px preview"
                        >
                    </a>
                </td>
                <td>
                    {{$slider->featured_name}}
                </td>
                <td>
                    {{$slider->created_at}}
                </td>
                <td>
                    <a href="javascript:void(0);"
                       class="tab-link btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon edit_btn"
                       data-item="{{$slider}}"
                       data-type="{{$slider->modelToSlug($slider->model_type)}}"
                       data-thumbnail ="{{$slider->getFirstMediaUrl('image')}}"
                    >
                        <span>
                            <i class="la la-edit"></i>
                            <span>Edit</span>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm m-1	p-2 m-btn m-btn--icon switchOne" data-action="delete" data-id="{{$slider->id}}">
                        <span>
                            <span>Delete</span>
                        </span>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
