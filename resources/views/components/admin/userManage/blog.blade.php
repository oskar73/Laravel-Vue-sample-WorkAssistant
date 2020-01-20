<div class="table-responsive">
    <table class="table table-hover ajaxTable datatable front-dt">
        <thead>
        <tr>
            <th>
                #
            </th>
            <th>
                Category
            </th>
            <th>
                Title
            </th>
            <th>
                <i class="fa fa-eye tooltip_3" title="View Count"></i>
            </th>
            <th>
                <i class="fa fa-heart tooltip_3" title="Liked Count"></i>
            </th>
            <th>
                <i class="fa fa-comment tooltip_3" title="Total Comments Count"></i>
            </th>
            <th>
                <i class="fa fa-bell tooltip_3" title="Subscribers Count"></i>
            </th>
            <th>
                Type
            </th>
            <th>
                Featured
            </th>
            <th>
                Published
            </th>
            <th>
                Status
            </th>
            <th>
                Live Date
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
            @foreach($blogPosts as $key=>$blogPost)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$blogPost->category->name}}</td>
                    <td>{{$blogPost->title}}</td>
                    <td>{{$blogPost->view_count}}</td>
                    <td>{{$blogPost->favoriters_count}}</td>
                    <td>{{$blogPost->comments_count}}</td>
                    <td>{{$blogPost->subscribers_count}}</td>
                    <td>
                        @if($blogPost->is_free==1)
                            <span class="c-badge c-badge-info">Free</span>
                        @else
                            <span class="c-badge c-badge-success" >Paid</span>
                        @endif
                    </td>
                    <td>
                        @if($blogPost->featured==1)
                            <span class="c-badge c-badge-success ">Featured</span>
                        @endif
                    </td>
                    <td>
                        @if($blogPost->is_published==1)
                            <span class="c-badge c-badge-success ">Published</span>
                        @else
                            <span class="c-badge c-badge-info " >Draft</span>
                        @endif
                    </td>
                    <td>
                        @if($blogPost->status=="approved")
                            <span class="c-badge c-badge-success ">Approved</span>
                        @elseif($blogPost->status=='pending')
                            <span class="c-badge c-badge-info " >Pending</span>
                        @elseif($blogPost->status=='denied')
                            <span class="c-badge c-badge-danger " >Denied</span>
                        @endif
                    </td>
                    <td>{{$blogPost->visible_date}}</td>
                    <td>{{$blogPost->created_at->toDateString()}}</td>
                    <td>
                        <a href="{{route('admin.blog.post.show', $blogPost->id)}}" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill tooltip_3" title="Detail">
                            <i class="la la-eye"></i>
                        </a>
                        <a href="{{route('admin.blog.post.edit', $blogPost->id)}}" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill tooltip_3" title="Edit">
                            <i class="la la-edit"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
