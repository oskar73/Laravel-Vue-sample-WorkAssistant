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
                    Product Type
                </th>
                <th>
                    Name
                </th>
                <th>
                    Price Detail
                </th>
                <th>
                    Order ID
                </th>
                <th>
                    Status
                </th>
                <th>
                    Due Date
                </th>
                <th>
                    Created At
                </th>
                <th>
                    Action
                </th>
            </tr>
        </thead>
        <tbody class="loading-tbody"><tr><td colspan="{{$user?10:9}}" style="height:200px;"></td></tr></tbody>
    </table>
</div>
