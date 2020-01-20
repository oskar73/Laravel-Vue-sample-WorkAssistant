<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable {{$selector}}">
        <thead>
        <tr>
            <th>
                Name
            </th>
            <th>
                Domain
            </th>
            @if($user==1)
            <th>
                User
            </th>
            @endif
            <th>
                Status
            </th>
            <th>
                Status By Owner
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
        <tbody class="loading-tbody"><tr><td colspan="{{$user==1?9:8}}" style="height:200px;"></td></tr></tbody>
    </table>
</div>
