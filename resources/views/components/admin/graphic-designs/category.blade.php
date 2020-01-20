<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable {{$selector}}">
        <thead>
        <tr>
            <th>
                Title
            </th>
            <th>
                Thumbnail
            </th>
            <th>
                Dimension
            </th>
            <th>
                Status
            </th>
            <th>
                Masks
            </th>
            <th>
                Icons
            </th>
            <th>
                Images
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
        @foreach($graphics as $graphic)
            <tr>
                <td>{{$graphic->title}}</td>
                <td class="tw-flex tw-justify-center">
                    <img src="{{ $graphic->getFirstMediaUrl('thumbnail') }}" class="tw-w-36" alt="thumbnail">
                </td>
                <td>({{$graphic->width}}, {{ $graphic->height }})</td>
                <td>{{$graphic->status}}</td>
                <td>{{ $graphic->getMedia('masks')->count()  }}</td>
                <td>{{ $graphic->getMedia('icons')->count() }}</td>
                <td>{{ $graphic->getMedia('images')->count() }}</td>
                <td>{{ $graphic->created_at->format('Y-m-d') }}</td>
                <td class="tw-w-96">
                    <a href="{{ route('admin.graphics.front.index', $graphic->slug) }}"
                       class="tab-link btn btn-outline-primary btn-sm m-1	p-2 m-btn m-btn--icon"
                    >
                        <span>
                            <i class="la la-cog"></i>
                            <span>Front Settings</span>
                        </span>
                    </a>
                    <a href="javascript:void(0);"
                       class="tab-link btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon edit_btn"
                       data-category="{{$graphic}}"
                       data-thumbnail="{{$graphic->getFirstMediaUrl('thumbnail')}}"
                    >
                        <span>
                            <i class="la la-edit"></i>
                            <span>Edit</span>
                        </span>
                    </a>
                    <a href="{{route('admin.graphics.delete', $graphic->id)}}" class="btn btn-outline-danger btn-sm m-1	p-2 m-btn m-btn--icon deleteBtn" data-action="delete">
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
