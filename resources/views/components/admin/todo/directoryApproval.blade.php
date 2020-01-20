<div>
    <div class="table-responsive">
        <table class="table table-hover table-bordered table-item-center">
            <thead>
            <tr>
                <th class="text-center">
                    Listing Title
                </th>
                <th class="text-center">
                    User
                </th>
                <th class="text-center">
                    Created Date
                </th>
                <th class="text-center">
                    Do Now
                </th>
            </tr>
            </thead>
            <tbody id="table_body">
            @foreach($todos as $todo)
                <tr>
                    <td>{{$todo->title}}</td>
                    <td>
                        <a href="{{route('admin.userManage.detail', $todo->creator->id??'')}}">
                            {{$todo->creator->name??auth()->user()->name}} ({{$todo->creator->email??auth()->user()->email}})
                        </a>
                    </td>
                    <td>{{$todo->created_at->toDateTimeString()}}</td>
                    <td>
                        <a href="{{route('admin.directory.listing.preview', $todo->slug)}}" target="_blank" class="btn btn-outline-success m-btn--sm"><i class="fa fa-eye"></i> Preview</a>
                        <a href="{{route('admin.directory.listing.approve', $todo->id)}}" class="btn btn-outline-success m-btn--sm">Approve</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
{{$todos->links()}}
