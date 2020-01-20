<div class="table-responsive mt-4">
    <table class="table table-hover ajaxTable datatable border-top-none-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Post title</th>
            <th class="text-right"><i class="fa fa-eye tooltip_3" title="View Count"></i></th>
            <th class="text-right"><i class="fa fa-heart tooltip_3" title="Favorite Count"></i></th>
            <th class="text-right"><i class="fa fa-comment tooltip_3" title="Comments Count"></i></th>
            <th>Author</th>
            <th class="text-right"> Date</th>
        </tr>
        </thead>
        <tbody>
            @forelse($posts as $key=>$post)
                <tr>
                    <td>{{$key+1+(($pageNum-1)*$perpage)}}</td>
                    <td class="text-left"><a href="{{route('blog.detail', $post->slug)}}">{{$post->title}}</a></td>
                    <td class="text-right">{{$post->view_count}}</td>
                    <td class="text-right">{{ $post->favoriters_count }}</td>
                    <td class="text-right">{{ $post->approved_comments_count }}</td>
                    <td class="text-left">{{ $post->user->name }}</td>
                    <td class="text-right">{{ $post->created_at->toDateString() }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">No posts</td></tr>
            @endforelse
        </tbody>
    </table>
    {{$posts->links()}}
</div>

