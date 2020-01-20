<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable connected_domains">
        <thead>
            <tr>
                <th>
                    Name
                </th>
                <th>
                    Used Website
                </th>
                <th>
                    Connected At
                </th>
                <th class="no-sort">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
        @foreach($domains as $domain)
            <tr>
                <td>{{$domain->name}}</td>
                <td>{{$domain->website->name}}</td>
                <td>{{$domain->created_at}}</td>
                <td>
                    <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm m-1	p-2 m-btn m-btn--icon disconnect" data-id="{{$domain->id}}">
                        <span>
                            <i class="la la-remove"></i>
                            <span>Disconnect</span>
                        </span>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
