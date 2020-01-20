<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable {{$selector}}">
        <thead>
            <tr>
                <th>
                    Order | Package
                </th>
                <th>
                    Name
                </th>
                <th>
                    status
                </th>
                <th>
                    Created At
                </th>
                <th>
                    Action
                </th>
            </tr>
        </thead>
        <tbody class="loading-tbody">
            @foreach($items as $item)
                <tr>
                    <td>
                        @if($item->order_item_id!=0)
                            <a href="{{route('user.purchase.order.detail', $item->orderItem->order_id)}}">Order #{{$item->orderItem->order_id}}</a>
                        @elseif($item->package_pid!=0 && $item->pPackage->id)
                            @if($item->pPackage->package==1)
                                <a href="{{route('user.purchase.package.detail', $item->pPackage->id)}}">{{$item->pPackage->getName()}}</a>
                            @else
                                <a href="{{route('user.purchase.readymade.detail', $item->pPackage->id)}}">{{$item->pPackage->getName()}}</a>
                            @endif
                        @endif

                    </td>
                    <td>{{$item->getName()}}</td>
                    <td>
                        @if($item->status=='active')
                            <span class="c-badge c-badge-success">Active</span>
                        @else
                            <span class="c-badge c-badge-info">{{$item->status}}</span>
                        @endif
                    </td>
                    <td>{{$item->created_at->toDateTimeString()}}</td>
                    <td>
                        <a href="{{route('user.purchase.plugin.detail', $item->id)}}" class="btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon">
                            <span>
                                <i class="la la-eye"></i>
                                <span>Detail</span>
                            </span>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
