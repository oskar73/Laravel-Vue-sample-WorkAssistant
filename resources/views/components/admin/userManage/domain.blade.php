<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable front-dt">
        <thead>
        <tr>
            <th>
                #
            </th>
            <th>
                DomainID
            </th>
            <th>
                Name
            </th>
            <th>
                Transaction ID
            </th>
            <th>
                DNS Pointed
            </th>
            <th>
                Charged Amount (NC)
            </th>
            <th>
                Charged Amount (BB)
            </th>
            <th>
                Expired At
            </th>
            <th>
                Action
            </th>
        </tr>
        </thead>
        <tbody class="loading-tbody">
            @foreach($user->domains as $key=>$domain)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$domain->domainID}}</td>
                    <td>{{$domain->name}}</td>
                    <td>{{$domain->transactionID}}</td>
                    <td>{{$domain->pointed}}</td>
                    <td>{{formatNumber($domain->chargedAmountNC)}}</td>
                    <td>{{formatNumber($domain->chargedAmountBB)}}</td>
                    <td>{{$domain->expired_at}}</td>
                    <td>
                        <a href="{{route('admin.domainList.show', $domain->id)}}" class="btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon">
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
