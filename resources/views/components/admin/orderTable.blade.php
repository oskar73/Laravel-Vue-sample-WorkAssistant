<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable {{$selector}}">
        <thead>
            <tr>
                <th>
                    ID
                </th>
                @if($user==1)
                <th>
                    User
                </th>
                @endif
                <th>
                    Payment
                </th>
                <th>
                    Price Detail
                </th>
                <th>
                    Total Quantity
                </th>
                <th>
                    Created At
                </th>
                <th>
                    Action
                </th>
            </tr>
        </thead>
        <tbody class="loading-tbody"><tr><td colspan="{{$user==1?7:6}}" style="height:200px;"></td></tr></tbody>
    </table>
</div>
