<div class="table-responsive" id="app">
    <table class="table table-hover ajaxTable datatable {{$selector}}">
        <thead>
            <tr>
                <th class="no-sort">
                    <input name="select_all" class="selectAll checkbox" value="1" type="checkbox" data-area="{{$selector}}">
                </th>
                <th>
                    Preview
                </th>
                <th>
                    Category
                </th>
                <th>
                    Name
                </th>
                {{-- <th>
                    Status
                </th> --}}
                <th>
                    Created At
                </th>
                <th class="no-sort">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
        @foreach($sections as $section)
            <tr>
                <td><input type="checkbox" class="checkbox" data-id="{{$section->id}}"></td>
                <td>
                    <div class="section_preview_item cursor-pointer">
                        <div class="section-preview-base">
                            <component is="{{$section->name}}" :properties="{{$section}}"></component>
                        </div>
                    </div>
                </td>
                <td>{{$section->category->name}}</td>
                <td>{{$section->name}}</td>
                {{-- <td>
                    @if($section->status===1)
                        <span class="c-badge c-badge-success hover-handle">Active</span>
                        <a href="javascript:void(0);" class="h-cursor c-badge c-badge-danger d-none origin-none down-handle hover-box switchOne" data-action="inactive">Inactive?</a>
                    @else
                        <span class="c-badge c-badge-danger hover-handle" >InActive</span>
                        <a href="javascript:void(0);" class="h-cursor c-badge c-badge-success d-none origin-none down-handle hover-box switchOne" data-action="active">Active?</a>
                    @endif
                </td> --}}
                <td>{{$section->created_at}}</td>
                <td>
                    <a href="{{route('admin.template.section.edit', $section->id)}}"
                       class="tab-link btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon"
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
