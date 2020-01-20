<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable {{$selector}}">
        <thead>
            <tr>
                <th class="no-sort">
                    <input name="select_all" class="selectAll checkbox" value="1" type="checkbox" data-area="{{$selector}}">
                </th>
                <th>
                    Name
                </th>
                <th>
                    Category
                </th>
                <th>
                    Status
                </th>

                <th class="no-sort">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
        @foreach($items as $key=>$item)
            <tr>
                <td><input type="checkbox" class="checkbox" data-id="{{$item->id}}"></td>
                <td>
                    {{$item->name}}
                    <a class="text-black hover-none" href="{{route('admin.template.item.preview', $item->slug)}}" target="_blank"><i class="la la-external-link"></i></a>
                </td>
                <td>
                    @if($item->category->parent_id!==0)
                        {{$item->category->category->name}} --> <br>
                    @endif
                    {{$item->category->name}}
                </td>
                <td>
                    @if($item->status===1)
                        <span class="c-badge c-badge-success hover-handle">Active</span>
                        <a href="javascript:void(0);" class="h-cursor c-badge c-badge-danger d-none origin-none down-handle hover-box switchOne" data-action="inactive">Inactive?</a>
                    @else
                        <span class="c-badge c-badge-danger hover-handle" >InActive</span>
                        <a href="javascript:void(0);" class="h-cursor c-badge c-badge-success d-none origin-none down-handle hover-box switchOne" data-action="active">Active?</a>
                    @endif
                </td>

                <td>
                    <a href="{{route('admin.template.item.editPages', $item->id)}}" class="tab-link btn btn-info btn-sm m-1	p-2 m-btn m-btn--icon edit_btn">
                        <span>
                            <i class="la la-edit"></i>
                            <span>Design</span>
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
