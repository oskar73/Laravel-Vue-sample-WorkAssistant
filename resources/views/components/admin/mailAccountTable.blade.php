<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable {{$selector}}">
        <thead>
        <tr>
            @if($all)
            <th>
                Domain
            </th>
            @endif
            <th>
                Email Address
            </th>
            <th>
                Name
            </th>
            <th>
                Quota (MB)
            </th>
            <th>
                Status
            </th>
            <th>
                Action
            </th>
        </tr>
        </thead>
        <tbody class="loading-tbody"><tr><td colspan="{{$all?6:5}}" style="height:200px;"></td></tr></tbody>
    </table>
</div>
