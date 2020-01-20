<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable {{$selector}}">
        <thead>
            <tr>
                <th class="no-sort">
                    <input name="select_all" class="selectAll checkbox" value="1" type="checkbox" data-area="{{$selector}}">
                </th>
                <th>
                    Name
                </th>
                <th>
                    Users
                </th>
                <th>
                    Image
                </th>
                <th>
                    Properties
                </th>
                @if($subteam == 0)
                <th>
                    SubTeams
                </th>
                @endif
                <th>
                    Status
                </th>
                <th>
                    Created At
                </th>
                <th class="no-sort">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
        @foreach($teams as $team)
            <tr>
                <td><input type="checkbox" class="checkbox" data-id="{{$team->id}}"></td>
                <td>{{$team->name}}</td>
                <td>
                    @foreach($team->users->where('pivot.role', 'client') as $client)
                        <img src="{{$client->avatar()}}" alt="{{$client->name}}" class="tooltip_1 rounded-circle w-50px" title="{{$client->name}} (Client)">
                    @endforeach
                    @foreach($team->users->where('pivot.role', 'employee') as $employee)
                        <img src="{{$employee->avatar()}}" alt="{{$employee->name}}" class="tooltip_1 rounded-circle w-50px" title="{{$employee->name}} (Employee)">
                    @endforeach
                    @foreach($team->users->where('pivot.role', 'user') as $user)
                        <img src="{{$user->avatar()}}" alt="{{$user->name}}" class="tooltip_1 rounded-circle w-50px" title="{{$user->name}} (User)">
                    @endforeach
                </td>
                <td>
                    <a href="{{$team->getImage()}}" class="width-150 progressive replace m-auto">
                        <img src="{{$team->getImage('thumb')}}"
                             alt="{{$team->name}}"
                             class="width-150 preview"
                        >
                    </a>
                </td>
                <td>
                    @foreach($team->properties as $property)
                        <span class="c-badge c-badge-info">{{$property->name}}</span>
                    @endforeach
                </td>
                @if($subteam == 0)
                <td>
                    <a href="{{route('admin.teamManage.team.subteam', $team->slug)}}">{{$team->sub_teams_count}} Sub Teams</a>
                </td>
                @endif
                <td>
                    @if($team->status===1)
                        <span class="c-badge c-badge-success hover-handle">Active</span>
                        <a href="javascript:void(0);" class="h-cursor c-badge c-badge-danger d-none origin-none down-handle hover-box switchOne" data-action="inactive">Inactive?</a>
                    @else
                        <span class="c-badge c-badge-danger hover-handle" >InActive</span>
                        <a href="javascript:void(0);" class="h-cursor c-badge c-badge-success d-none origin-none down-handle hover-box switchOne" data-action="active">Active?</a>
                    @endif
                </td>
                <td>{{$team->created_at}}</td>
                <td>
                    <a href="{{route('admin.teamManage.team.edit', $team->id)}}"
                       class="tab-link btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon edit_btn"
                    >
                        <span>
                            <i class="la la-edit"></i>
                            <span>Edit</span>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm m-1	p-2 m-btn m-btn--icon switchOne" data-action="delete">
                        <span>
                            <span>Delete</span>
                        </span>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
