<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable front-dt">
        <thead>
        <tr>
            <th>
                #
            </th>
            <th>
                Name
            </th>
            <th>
                Domain
            </th>
            <th>
                Status
            </th>
            <th>
                Storage
            </th>
            <th>
                Created At
            </th>
            <th>
                Action
            </th>
        </tr>
        </thead>
        <tbody class="loading-tbody">
            @foreach($user->websites as $key=>$website)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$website->name}}</td>
                    <td>{{$website->domain}} <a href="//{{$website->domain}}" target="_blank" class="hover-none"> <i class='la la-external-link'></i></a></td>
                    <td>
                        <span class="c-badge c-badge-success">{{ucfirst($website->status)}}</span>
                    </td>
                    <td>{!! $website->storageUsage() !!}</td>
                    <td>{{$website->created_at}}</td>
                    <td>
                        <a href="{{route('admin.website.list.show', $website->id)}}" class="btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon">
                        <span>
                            <i class="la la-eye"></i>
                            <span>Detail</span>
                        </span>
                        </a>
                        <a href="{{route('admin.website.list.edit', $website->id)}}" class="btn btn-outline-success btn-sm m-1	p-2 m-btn m-btn--icon">
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
