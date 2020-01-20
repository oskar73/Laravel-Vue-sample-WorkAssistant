<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable {{$selector}}">
        <thead>
        <tr>
            <th>
                <input name="select_all" class="selectAll checkbox" value="1" type="checkbox" data-area="{{$selector}}">
            </th>
            @if($user)
            <th>
                User
            </th>
            @endif
            <th>
                Date Time
            </th>
            <th>
                IP address
            </th>
            <th>
                Device
            </th>
            <th>
                Action
            </th>
        </tr>
        </thead>
        <tbody class="loading-tbody"><tr><td colspan="{{$user?6:5}}" style="height:200px;"></td></tr></tbody>
    </table>
</div>
