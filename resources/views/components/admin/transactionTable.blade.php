<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable {{$selector}}">
        <thead>
            <tr>
                @if($user)
                <th>
                    User
                </th>
                @endif
                <th>
                    Payment Gateway
                </th>
                <th>
                    Amount
                </th>
                <th>
                    Invoice
                </th>
                <th>
                    Date
                </th>
                <th>
                    Action
                </th>
            </tr>
        </thead>
        <tbody class="loading-tbody"><tr><td colspan="{{$user? 6:5}}" style="height:200px;"></td></tr></tbody>
    </table>
</div>
