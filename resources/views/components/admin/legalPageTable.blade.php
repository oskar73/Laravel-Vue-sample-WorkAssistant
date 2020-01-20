<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable ">
        <thead>
            <tr>
                <th>
                    Title
                </th>
                <th class="no-sort">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{$item->title}}</td>
                <td>
                    <a href="{{route('admin.legalPage.edit', $item->slug)}}"
                       class="tab-link btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon edit_btn"
                    >
                        <span>
                            <i class="la la-edit"></i>
                            <span>Edit</span>
                        </span>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
