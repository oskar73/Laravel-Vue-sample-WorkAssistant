<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable {{$selector}}">
        <thead>
        <tr>
            <th>
                Domain
            </th>
            <th>
                Aliases
            </th>
            <th>
                Accounts
            </th>
            <th>
                Max Quota per Mailbox
            </th>
            <th>
                Total Quota
            </th>
            <th>
                Status
            </th>
            <th>
                Action
            </th>
        </tr>
        </thead>
        <tbody class="loading-tbody"><tr><td colspan="{{$user==1?8:7}}" style="height:200px;"></td></tr></tbody>
    </table>
</div>
