
<div>
    <div class="table-responsive">
        <table class="table table-hover table-bordered table-item-center">
            <thead>
            <tr>
                <th class="text-center">
                    Regarding purchase
                </th>
                <th class="text-center">
                    Offer Price
                </th>
                <th class="text-center">
                    Date
                </th>
                <th class="text-center">
                    Do Now
                </th>
            </tr>
            </thead>
            <tbody id="table_body">
                @foreach($todos as $todo)
                    <tr>
                        <td>
                            {{$todo->getName()}}
                        </td>
                        <td>
                            {{$todo->domain}} USD
                        </td>
                        <td>{{$todo->created_at->toDateTimeString()}}</td>
                        <td><a href="{{route('user.domain.search')}}" class="btn btn-outline-success m-btn--sm">Do Now</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
{{$todos->links()}}
