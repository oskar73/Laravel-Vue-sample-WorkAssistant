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
                Description
            </th>
            <th>
                Ad Type
            </th>
            <th>
                Price
            </th>
            <th>
                Active Listings
            </th>
            <th>
                Status
            </th>
            <th class="no-sort">
                Action
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($positions as $position)
            <tr>
                <td><input type="checkbox" class="checkbox" data-id="{{$position->id}}"></td>
                <td>{{$position->name}}</td>
                <td>{{$position->description}}</td>
                <td>
                    {{$position->type->name}} <br>
                    (Width:{{$position->type->width}}px, Height:{{$position->type->height}}px)
                </td>
                <td>
                    @foreach($position->approvedPrices as $price)
                        <div @if($price->standard)title="Standard Price" class="text-active tooltip_1" @endif>
                            <span>${{$price->price}}</span> / {{$price->getUnit()}}<br>
                        </div>
                    @endforeach
                </td>
                <td>
                    10
                </td>
                <td>
                    @if($position->status===1)
                        <span class="c-badge c-badge-success hover-handle">Active</span>
                        <a href="javascript:void(0);"
                           class="h-cursor c-badge c-badge-danger d-none origin-none down-handle hover-box switchOne"
                           data-action="inactive">Inactive?</a>
                    @else
                        <span class="c-badge c-badge-danger hover-handle">InActive</span>
                        <a href="javascript:void(0);"
                           class="h-cursor c-badge c-badge-success d-none origin-none down-handle hover-box switchOne"
                           data-action="active">Active?</a>
                    @endif
                </td>
                <td>
                    <a href="{{route('admin.newsletterAds.position.edit', $position->id)}}"
                       class="tab-link btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon"
                    >
                        <span>
                            <i class="la la-edit"></i>
                            <span>Edit</span>
                        </span>
                    </a>
                    <a href="javascript:void(0);"
                       class="btn btn-outline-danger btn-sm m-1	p-2 m-btn m-btn--icon switchOne"
                       data-action="delete">
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
