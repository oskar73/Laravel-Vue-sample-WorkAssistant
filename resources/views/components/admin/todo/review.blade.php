
<div>
    <div class="table-responsive">
        <table class="table table-hover table-bordered table-item-center">
            <thead>
            <tr>
                <td>Product</td>
                <td>Rating</td>
                <td>User</td>
                <td>Date</td>
                <td>Do Now</td>
            </tr>
            </thead>
            <tbody id="table_body">
            @foreach($todos as $todo)
                <tr>
                    <td>{!! $todo->getProduct() !!}</td>
                    <td>{{$todo->rating}}</td>
                    <td>
                        @if(isset($todo->user->id))
                            <a href="{{route('admin.userManage.detail', $todo->user->id)}}">
                                {{$todo->user->name}} ({{$todo->user->email}})
                            </a>
                        @else
                            {{$todo->name}} (Unregistered) <br>
                            {{$todo->email}}
                        @endif
                    </td>
                    <td>{{$todo->updated_at->toDateTimeString()}}</td>
                    <td>
                        <a href="{{route('admin.review.show', $todo->id)}}" class="btn btn-outline-success m-btn--sm">Do Now</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
{{$todos->links()}}

