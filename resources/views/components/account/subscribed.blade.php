@if($categories->count())
    <div class="container-fluid mt-5">
        <p class="font-size20 border-bottom d-inline-block">Subscribed Blog Categories!</p><br>
        @foreach($categories as $category)
            <a href="{{route('blog.category', $category->slug)}}" target="_blank">{{$category->name}}</a>
            <span class="h-cursor ml-3 unsubscribeBtn" title="Unsubscribe" data-id="{{$category->id}}" data-type="category">❌</span><br>
        @endforeach
    </div>
@endif
@if($posts->count())
    <div class="container-fluid mt-5">
        <p class="font-size20 border-bottom d-inline-block">Subscribed Blog Posts!</p><br>
        @foreach($posts as $post)
            <a href="{{route('blog.detail', $post->slug)}}" target="_blank">{{$post->title}}</a>
            <span class="h-cursor ml-3 unsubscribeBtn" title="Unsubscribe" data-id="{{$post->id}}" data-type="post">❌</span><br>
        @endforeach
    </div>
@endif
@if($authors->count())
    <div class="container-fluid mt-5">
        <p class="font-size20 border-bottom d-inline-block">Subscribed Blog Authors!</p> <br>
        @foreach($authors as $author)
            <a href="{{route('blog.author', $author->username)}}" target="_blank">{{$author->name}}</a>
            <span class="h-cursor ml-3 unsubscribeBtn" title="Unsubscribe" data-id="{{$author->id}}" data-type="author">❌</span><br>
        @endforeach
    </div>
@endif
