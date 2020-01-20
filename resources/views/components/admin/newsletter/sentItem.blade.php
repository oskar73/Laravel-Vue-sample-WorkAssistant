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
                Subject
            </th>
            <th>
                Description
            </th>
            <th>
                Sent At
            </th>
            <th class="width-70px">
                Preview
            </th>
            <th class="no-sort">
                Action
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td><input type="checkbox" class="checkbox" data-id="{{$item->id}}"></td>
                <td>
                    {{$item->name}}
                </td>
                <td>
                    {{$item->subject}}
                </td>
                <td>
                    {{$item->description}}
                </td>
                <td>
                    {{$item->sent_at}}
                </td>
                <td>
                    <a href="{{route('admin.newsletter.item.preview', $item->slug)}}" target="_blank"
                       class="btn btn-primary btn-sm m-1 p-2 m-btn m-btn--icon">
                        <span>
                            <i class="la la-external-link"></i>
                            <span>Preview</span>
                        </span>
                    </a>
                </td>
                <td>
                    <a href="javascript:void(0);"
                       class="btn btn-outline-danger btn-sm m-1	p-2 m-btn m-btn--icon switchOne delete_btn"
                       data-slug="{{$item->slug}}"
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
