<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable {{ $selector }}">
        <thead>
        <tr>
            <th class="no-sort">
                <input name="select_all" class="selectAll checkbox" value="1" type="checkbox"
                       data-area="{{ $selector }}">
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
            <th>
                Featured
            </th>
            <th>
                New
            </th>
            <th class="no-sort">
                Action
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach ($templates as $key => $template)
            <tr>
                <td><input type="checkbox" class="checkbox" data-id="{{ $template->id }}"></td>
                <td>
                    {{ $template->name }}
                    <a class="text-black hover-none"
                       href="{{ route('admin.template.item.preview', $template->slug) }}" target="_blank"><i
                                class="la la-external-link"></i></a>
                </td>
                <td>
                    @if ($template->category->parent_id != 0)
                        {{ $template->category->category->name }} --> <br>
                    @endif
                    {{ $template->category->name }}
                </td>
                <td>
                    @if ($template->status === 1)
                        <span class="c-badge c-badge-success hover-handle">Active</span>
                        <a href="javascript:void(0);"
                           class="h-cursor c-badge c-badge-danger d-none origin-none down-handle hover-box switchOne"
                           data-action="inactive">Inactive?</a>
                    @else
                        <span class="c-badge c-badge-danger hover-handle">InActive</span>
                        <a href="javascript:void(0);"
                           class="h-cursor c-badge c-badge-success d-none origin-none down-handle hover-box switchOne"
                           data-action="active">Active?</a>
                    @endif
                </td>
                <td>
                    @if ($template->featured === 1)
                        <span class="c-badge c-badge-success hover-handle">Featured</span>
                        <a href="javascript:void(0);"
                           class="h-cursor c-badge c-badge-danger origin-none d-none down-handle hover-box switchOne"
                           data-action="unfeatured">Cancel?</a>
                    @else
                        <a href="javascript:void(0);" class="c-badge c-badge-success hover-vis hover-box switchOne"
                           data-action="featured">Featured?</a>
                    @endif
                </td>
                <td>
                    @if ($template->new === 1)
                        <span class="c-badge c-badge-info hover-handle">New</span>
                        <a href="javascript:void(0);"
                           class="h-cursor c-badge c-badge-danger origin-none d-none down-handle hover-box switchOne"
                           data-action="undonew">Cancel?</a>
                    @else
                        <a href="javascript:void(0);" class="c-badge c-badge-info hover-vis hover-box switchOne"
                           data-action="new">New?</a>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.template.item.editPages', $template->id) }}"
                       class="tab-link btn btn-info btn-sm m-1	p-2 m-btn m-btn--icon">
                            <span>
                                <i class="la la-edit"></i>
                                <span>Design</span>
                            </span>
                    </a>
                    <a href="" data-url="{{ route('admin.template.item.edit', $template->id) }}" data-toggle="modal"
                       data-target="#editTemplateModal"
                       class="tab-link btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon">
                            <span>
                                <i class="la la-edit"></i>
                                <span>Edit</span>
                            </span>
                    </a>
                    <a href="javascript:void(0);"
                       class="btn btn-outline-danger btn-sm m-1	p-2 m-btn m-btn--icon switchOne"
                       data-action="delete">
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
