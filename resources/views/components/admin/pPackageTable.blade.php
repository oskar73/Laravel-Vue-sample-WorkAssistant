<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable {{$selector}}">
        <thead>
            <tr>
                @if($user==1)
                <th>
                    User
                </th>
                @endif
                <th>
                    Order
                </th>
                <th>
                    Name
                </th>
                <th>
                    Payment
                </th>
                <th>
                    status
                </th>
                <th>
                    Created At
                </th>
                <th>
                    Due Date
                </th>
                <th>
                    Action
                </th>
            </tr>
        </thead>
        <tbody class="loading-tbody"><tr><td colspan="{{$user==1?8:7}}" style="height:200px;"></td></tr></tbody>
    </table>
</div>
