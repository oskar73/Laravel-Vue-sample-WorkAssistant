<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable {{$selector}}">
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Subject</th>
            <th>Sent At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody class="loading-tbody">
        @foreach($items as $item)
            <tr>
                <td>{{$item->name}}</td>
                <td>{{$item->description}}</td>
                <td>{{$item->subject}}</td>
                <td>{{date('Y-m-d H:i:s', strtotime($item->sent_at))}}</td>
                <td>
                    <a href="{{route('newsletter.item.preview', $item->slug)}}" target="_blank"
                       class="btn btn-primary btn-sm m-1 p-2 m-btn m-btn--icon">
                        <span>
                            <i class="la la-external-link"></i>
                            <span>Preview</span>
                        </span>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
