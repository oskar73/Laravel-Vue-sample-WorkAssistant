<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable {{$selector}}">
        <form class="filter_form" id="filter_form_{{ $selector }}"></form>
        <thead>
        <tr>
            <th></th>
            <th><input type="text" class="form-control" placeholder="PIN Number" form="filter_form_{{ $selector }}" name="f_pin"></th>
{{--            <th></th>--}}
            <th><input type="text" class="form-control" placeholder="Username" form="filter_form_{{ $selector }}" name="f_username"></th>
            <th><input type="text" class="form-control" placeholder="Name" form="filter_form_{{ $selector }}" name="f_name"></th>
            <th><input type="text" class="form-control" placeholder="Email" form="filter_form_{{ $selector }}" name="f_email"></th>
            <th>
                <select name="role" id="filter_role" class="form-control width-100px px-0" form="filter_form_{{ $selector }}">
                    <option value="">All</option>
                    <option value="1">Admin</option>
                    <option value="2">Client</option>
                    <option value="3">Employer</option>
                    <option value="4">User</option>
                </select>
            </th>
            <th>
                <select name="filter_status" id="filter_status" class="form-control width-100px px-0" form="filter_form_{{ $selector }}">
                    <option value="">All</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </th>
            <th><input type="text" class="form-control" placeholder="From Date" form="filter_form_{{ $selector }}" name="f_from_date"></th>
            <th><button  type="submit" class="btn btn-outline-success flt_btn" form="filter_form_{{ $selector }}">Filter</button></th>
        </tr>
        <tr>
            <th>
                <input name="select_all" class="selectAll checkbox" value="1" type="checkbox" data-area="{{$selector}}">
            </th>
            <th>
                PIN Number
            </th>
{{--            <th>--}}
{{--                Image--}}
{{--            </th>--}}
            <th>
                Username
            </th>
            <th>
                Name
            </th>
            <th>
                Email
            </th>
            <th>
                Role
            </th>
            <th>
                Status
            </th>
            <th>
                Created At
            </th>
            <th>
                Action
            </th>
        </tr>
        </thead>
        <tbody class="loading-tbody"><tr><td colspan="10" style="height:200px;"></td></tr></tbody>
    </table>
</div>
