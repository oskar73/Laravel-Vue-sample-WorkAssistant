<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable {{$selector}}">
        <thead>
        <tr>
            <th>
                DomainID
            </th>
            <th>
                Name
            </th>
            @if($user==1)
            <th>
                User
            </th>
            @endif
            <th>
                Used Website
            </th>
            <th>
                OrderID
            </th>
            <th>
                TransactionID
            </th>
            <th>
                ChargedAmountNC
            </th>
            <th>
                ChargedAmountBB
            </th>
            <th>
                CreatedAt
            </th>
            <th>
                ExpiredAt
            </th>
            <th>
                Action
            </th>
        </tr>
        </thead>
        <tbody class="loading-tbody"><tr><td colspan="{{$user==1?11:10}}" style="height:200px;"></td></tr></tbody>
    </table>
</div>
