<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable my_domains">
        <thead>
        <tr>
            <th class="no-sort">
                <input name="select_all" class="selectAll checkbox" value="1" type="checkbox" data-area="my_domains">
            </th>
            <th>
                Name
            </th>
            <th>
              WhoisGuard?
            </th>
            <th>
                Expired At
            </th>
            <th class="no-sort">
                Action
            </th>
        </tr>
        </thead>
        <tbody>
            @foreach($domains as $domain)
            <tr>
                <td><input type="checkbox" class="checkbox" data-id="{{$domain->id}}"></td>
                <td>{{$domain->name}}</td>
                <td> @if($domain->whoisguardEnable)<span class="c-badge c-badge-success">Enabled</span> @endif</td>
                <td>{{$domain->expired_at}}</td>
                <td>
                    <a href="#/renew" data-id="{{$domain->id}}" data-area="renew" class="tab-link btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon renew_btn">
                        <span>
                            <i class="la la-refresh"></i>
                            <span>Renew</span>
                        </span>
                    </a>
                    <a href="{{route('user.domainList.show', $domain->id)}}" class="btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon">
                        <span>
                            <i class="la la-eye"></i>
                            <span>Detail</span>
                        </span>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
