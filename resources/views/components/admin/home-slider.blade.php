<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable">
        <thead>
        <tr>
            <th class="no-sort">
                <input name="select_all" class="selectAll checkbox" value="1" type="checkbox" >
            </th>
            <th> Image </th>
            <th> Title </th>
            <th> Description </th>
            <th> Active </th>
            <th class="no-sort"> Action </th>
        </tr>
        </thead>
        <tbody>
            @foreach($sliders as $slider)
                <tr>
                    <td><input type="checkbox" class="checkbox" data-id="{{$slider->id}}"></td>
                    <td>
                        <a href="{{$slider->getFirstMediaUrl('image')}}" class="w-100 progressive replace">
                            <img src="{{$slider->getFirstMediaUrl('image')}}" class="width-150 preview">
                        </a>
                    </td>
                    <td>
                        {{$slider->title}}
                    </td>
                    <td>
                        {{$slider->description}}
                    </td>
                    <td>
                        @if($slider->status===1)
                            <span class="c-badge c-badge-success hover-handle">Active</span>
                            <a href="javascript:void(0);" class="h-cursor c-badge c-badge-danger d-none origin-none down-handle hover-box switchOne" data-action="inactive">Inactive?</a>
                        @else
                            <span class="c-badge c-badge-danger hover-handle" >InActive</span>
                            <a href="javascript:void(0);" class="h-cursor c-badge c-badge-success d-none origin-none down-handle hover-box switchOne" data-action="active">Active?</a>
                        @endif
                    </td>
                    <td>
                        <a href="javascript:void(0);"
                           class="tab-link btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon edit_btn"
                           data-item="{{$slider}}"
                           data-image="{{$slider->getFirstMediaUrl('image')}}"
                        >
                            <span>
                                <i class="la la-edit"></i>
                                <span>Edit</span>
                            </span>
                        </a>
                        <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm m-1	p-2 m-btn m-btn--icon switchOne" data-action="delete">
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
