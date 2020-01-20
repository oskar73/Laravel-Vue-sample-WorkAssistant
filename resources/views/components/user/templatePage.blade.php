<div class="table-responsive">
    <table class="table table-bordered ajaxTable datatable {{$selector}}">
        <thead>
            <tr>
                <th class="no-sort">
                    <input name="select_all" class="selectAll checkbox" value="1" type="checkbox" data-area="{{$selector}}">
                </th>
                <th>ID</th>
                <th>Page Name</th>
                <th>Status</th>
                <th>Parent Page</th>
                <th>Page Builder</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pages as $page)
                <tr>
                    <td><input type="checkbox" class="checkbox" data-id="{{$page->id}}"></td>
                    <td>{{$page->id}}</td>
                    <td>{{$page->name}}</td>
                    <td>
                        @if($page->status)
                            <span class="c-badge c-badge-success hover-handle">Active</span>
                            <a href="javascript:void(0);" class="h-cursor c-badge c-badge-danger d-none origin-none down-handle hover-box switchOne" data-action="inactive">Inactive?</a>
                        @else
                            <span class="c-badge c-badge-danger hover-handle" >InActive</span>
                            <a href="javascript:void(0);" class="h-cursor c-badge c-badge-success d-none origin-none down-handle hover-box switchOne" data-action="active">Active?</a>
                        @endif
                    </td>
                    <td>
                        @if($page->parent_id===0)
                            <span class="c-badge c-badge-success">Main Page</span>
                        @else
                             <span class="c-badge c-badge-info">{{$page->parent->name}}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{route('user.template.item.preview', ['slug'=>$template->slug, 'url'=>$page->url])}}"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill"
                           title="View"
                           target="_blank"
                        >
                            <i class="la la-external-link"></i>
                        </a>
                        <a href="{{route('user.template.page.editContent', ['id'=>$page->id,'type'=>'builder'])}}" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="Edit Page">
                            <i class="la la-edit"></i>
                        </a>
                    </td>
                    <td>
                        <a href="javascript:void(0);"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill edit_page_btn"
                           data-id="{{$page->id}}"
                           title="View"
                        >
                            <i class="la la-edit"></i>
                        </a>
                        <a href="javascript:void(0);"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill switchOne"
                           data-action="delete"
                           title="View"
                        >
                            <i class="la la-remove"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">None pages</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

