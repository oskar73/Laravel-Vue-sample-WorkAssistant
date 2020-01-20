<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable front-dt">
        <thead>
        <tr>
            <th>
                #
            </th>
            <th>
                Spot
            </th>
            <th >
                Page
            </th>
            <th>
                Duration / Impression
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
        <tbody class="loading-tbody">
            @foreach($user->blogAdsListings as $key=>$listing)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$listing->spot->name}}</td>
                    <td>{{$listing->spot->getPageName()}}</td>
                    <td>
                        @if(json_decode($listing->price)->type==='impression')
                            Total Impressions:{{$listing->impression_number}} <br>
                            Current Impressions:{{$listing->current_number}} <br>
                        @else
                            @foreach($listing->events as $event)
                                {{$event->start_date}} ~ {{$event->end_date}} <br>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        @if($listing->status=='approved')
                            <span class="c-badge c-badge-success">Approved</span>
                        @elseif($listing->status=='pending')
                            <span class="c-badge c-badge-info" >Pending</span>
                        @elseif($listing->status=='denied')
                            <span class="c-badge c-badge-danger" >Pending</span>
                        @elseif($listing->status=='paid')
                            <span class="c-badge c-badge-info" >Pending</span>
                        @elseif($listing->status=='expired')
                            <span class="c-badge c-badge-warning" >Pending</span>
                        @endif

                    </td>
                    <td>{{$listing->created_at->toDateString()}}</td>
                    <td>
                        <a href="{{route('admin.blogAds.listing.show', $listing->id)}}" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill tooltip_3" title="Detail">
                            <i class="la la-eye"></i>
                        </a>
                        <a href="{{route('admin.blogAds.listing.edit', $listing->id)}}" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill tooltip_3" title="Edit">
                            <i class="la la-edit"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
