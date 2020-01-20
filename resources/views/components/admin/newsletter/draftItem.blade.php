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
                    <a href="{{route('admin.newsletter.item.preview', $item->slug)}}" target="_blank"
                       class="btn btn-primary btn-sm m-1 p-2 m-btn m-btn--icon">
                        <span>
                            <i class="la la-external-link"></i>
                            <span>Preview</span>
                        </span>
                    </a>
                </td>
                <td>
                    <a href="{{route('admin.newsletter.item.edit', ['slug' => $item->slug])}}"
                       class="btn btn-outline-info btn-sm m-1 p-2 m-btn m-btn--icon edit_btn">
                        <span>
                            <i class="la la-edit"></i>
                            <span>Edit</span>
                        </span>
                    </a>
                    <a href="{{route('admin.newsletter.item.design', ['slug' => $item->slug])}}"
                       class="btn btn-outline-secondary btn-sm m-1 p-2 m-btn m-btn--icon">
                        <span>
                            <i class="la la-crop"></i>
                            <span>Design</span>
                        </span>
                    </a>
                    <a href="{{route('admin.newsletter.item.review', ['slug' => $item->slug])}}"
                       class="btn btn-outline-warning btn-sm m-1 p-2 m-btn m-btn--icon send_btn">
                        <span>
                            <i class="la la-send"></i>
                            <span>Send</span>
                        </span>
                    </a>
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
