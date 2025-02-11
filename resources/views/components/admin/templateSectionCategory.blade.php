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
                    Sections
                </th>
                <th>
                    Recommended
                </th>
                <th>
                    Status
                </th>
                <th>
                    Limit per page
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
        @foreach($categories as $category)
            <tr>
                <td><input type="checkbox" class="checkbox" data-id="{{$category->id}}"></td>
                <td>{{$category->name}}</td>
                <td>{{$category->sectionCount}}</td>
                <td>
                    @if($category->recommended===1)
                        <span class="c-badge c-badge-success">Recommended</span>
                   @endif
                </td>
                <td>
                    @if($category->status===1)
                        <span class="c-badge c-badge-success hover-handle">Active</span>
                        <a href="javascript:void(0);" class="h-cursor c-badge c-badge-danger d-none origin-none down-handle hover-box switchOne" data-action="inactive">Inactive?</a>
                    @else
                        <span class="c-badge c-badge-danger hover-handle" >InActive</span>
                        <a href="javascript:void(0);" class="h-cursor c-badge c-badge-success d-none origin-none down-handle hover-box switchOne" data-action="active">Active?</a>
                    @endif
                </td>
                <td>{{ $category->limit_per_page }}</td>
                <td>{{$category->created_at}}</td>
                <td>
                    <a href="{{route('admin.template.section.create', $category->id)}}"
                       class="tab-link btn btn-outline-success btn-sm m-1	p-2 m-btn m-btn--icon"
                       data-category="{{$category}}"
                    >
                        <span>
                            <i class="la la-plus"></i>
                            <span>Add Section</span>
                        </span>
                    </a>
                    <a href="javascript:void(0);"
                       class="tab-link btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon edit_btn"
                       data-category="{{$category}}"
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
