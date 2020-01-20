<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Integration\Cart;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPackage;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\Social\Account as SocialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Session;
use Validator;

class BlogController extends Controller
{
    public $model;

    public function __construct()
    {
        $this->model = new BlogPost();

        if ($seo = option("blog.front.seo", null)) {
            view()->share('seo', $seo);
        }
    }

    public function index()
    {
        $posts = $this->model->with('media')
            ->withCount('approvedComments', 'favoriters')
            ->frontVisible()
            ->get();

        $featured_posts = $posts->sortByDesc("featured")->sortByDesc("created_at")->take(6)->values();
        $recent_posts = $posts->sortByDesc("created_at")->take(4)->values();
        $popular_posts = $posts->sortByDesc("view_count")->take(5)->values();
        $most_discussed_posts = $posts->sortByDesc("approved_comments_count")->take(5)->values();

        return view('frontend.blog.index', [
            'featured_posts' => $featured_posts,
            'recent_posts' => $recent_posts,
            'popular_posts' => $popular_posts,
            'most_discussed_posts' => $most_discussed_posts,
        ]);
    }

    public function ajaxPage(Request $request)
    {
        $posts = $this->model->with('media')
            ->withCount('approvedComments', 'favoriters')
            ->frontVisible()
            ->latest()
            ->paginate(10);

        return view('components.front.ajaxPost', compact("posts"))->render();
    }

    public function ajaxCategory(Request $request, $id)
    {
        $categories = BlogCategory::where("status", 1)->where(function ($query) use ($id) {
            return $query->where("id", $id)
                ->orWhere('parent_id', $id);
        })->get();

        $categoryIds = $categories->pluck('id')->toArray();

        $posts = $this->model->with('media')
            ->withCount('approvedComments', 'favoriters')
            ->whereIn("category_id", $categoryIds)
            ->frontVisible()
            ->latest()
            ->paginate(10);

        return view('components.front.ajaxPost', compact("posts"))->render();
    }

    public function ajaxTag(Request $request, $id)
    {
        $tag = BlogTag::where('id', $id)
            ->whereStatus(1)
            ->firstorfail();
        $posts = $tag->visiblePosts()->with('media')
            ->withCount('approvedComments', 'favoriters')
            ->latest()
            ->paginate(10);

        return view('components.front.ajaxPost', compact("posts"))->render();
    }

    public function ajaxAuthor(Request $request, $username)
    {
        $user = User::whereUsername($username)
            ->whereStatus("active")
            ->firstorfail();

        $posts = $user->visiblePosts()->with('media')
            ->withCount('approvedComments', 'favoriters')
            ->latest()
            ->paginate(10);

        return view('components.front.ajaxPost', compact("posts"))->render();
    }

    public function ajaxComment(Request $request, $slug)
    {
        $post = $this->model->frontVisible()
            ->whereSlug($slug)
            ->firstorfail();

        $comments = BlogComment::with('approvedComments', 'user.media', 'favorite_to_users')
            ->whereNull("parent_id")
            ->where("post_id", $post->id)
            ->where("status", 1)
            ->paginate(10);

        return view('components.front.blogComment', compact("comments"))->render();
    }

    public function detail($slug)
    {
        $post = $this->model
            ->with('media', 'user', 'approvedTags:name,slug')
            ->withCount('favoriters')
            ->frontVisible()
            ->whereSlug($slug)
            ->firstorfail();

        $blogKey = 'blog_' . $post->id;
        if (!Session::has($blogKey)) {
            $post->increment("view_count");
            Session::put($blogKey, 1);
        }
        $randomposts = $this->model->with('media')
            ->withCount('approvedComments', 'favoriters')
            ->frontVisible()
            ->inRandomOrder()
            ->take(2)
            ->get();

        $socialAccount = SocialAccount::where('username', $post->user->username)->orWhere('email', $post->user->email)->first();

        return view('frontend.blog.detail', compact("post", "randomposts", "socialAccount"));
    }

    public function category($slug)
    {
        $category = BlogCategory::with('media')
            ->withCount("visiblePosts")
            ->whereSlug($slug)
            ->whereStatus(1)
            ->firstorfail();

        return view('frontend.blog.category', compact("category"));
    }

    public function tag($slug)
    {
        $tag = BlogTag::whereSlug($slug)
            ->withCount("visiblePosts")
            ->whereStatus(1)
            ->firstorfail();

        return view('frontend.blog.tag', compact("tag"));
    }

    public function allPosts(Request $request)
    {
        if ($request->ajax()) {
            $slug = $request->category;
            $pageNum = $request->page;
            $perpage = 30;
            if ($slug == 'all') {
                $posts = $this->model->with('user:id,first_name,last_name,username')
                    ->withCount('approvedComments', 'favoriters')
                    ->frontVisible()
                    ->latest()
                    ->paginate($perpage);
            } else {
                $category = BlogCategory::whereStatus(1)->whereSlug($slug)->firstorfail();

                $posts = $this->model->with('user:id,first_name,last_name,username')
                    ->where('category_id', $category->id)
                    ->withCount('approvedComments', 'favoriters')
                    ->frontVisible()
                    ->latest()
                    ->paginate($perpage);
            }

            return view('components.front.blogTable', compact('posts', 'pageNum', 'perpage'))->render();
        }

        $categories = BlogCategory::whereStatus(1)
            ->select("id", "slug", "name")
            ->orderBy('order')
            ->get();

        return view('frontend.blog.allPosts', compact("categories"));
    }

    public function search(Request $request)
    {
        $perpage = 9;
        $keyword = $request->get('keyword');
        $posts = $this->model->with('user')
            ->withCount('approvedComments', 'favoriters')
            ->where(function ($query) use ($keyword) {
                $query->where("title", "LIKE", "%$keyword%");
                $query->orWhere("body", "LIKE", "%$keyword%");
            })
            ->frontVisible()
            ->latest()
            ->paginate($perpage);

        $data = view('components.front.searchPost', compact('posts'))->render();

        return response()->json([
            'status' => 1,
            'data' => $data,
        ]);
    }

    public function author($username)
    {
        $user = User::whereUsername($username)
            ->with("media")
            ->withCount('visiblePosts', 'approvedComments')
            ->whereStatus('active')
            ->firstorfail(['id', 'name', 'username']);

        return view('frontend.blog.author', compact("user", "username"));
    }

    public function getCommentForm(Request $request, $id)
    {
        return response()->json([
            'status' => 1,
            'data' => view('components.front.commentForm', ['post_id' => $id, 'comment_id' => $request->comment_id])->render(),
        ]);
    }

    public function postComment(Request $request, $id)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'comment' => 'required|max:6000',
                    'comment_id' => 'nullable|exists:blog_comments,id,parent_id,NULL,status,1,post_id,' . $id,
                ]
            );
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }

            $post = $this->model->frontVisible()
                ->whereId($id)
                ->firstorfail();

            $comment = new BlogComment();
            $comment->post_id = $post->id;
            $comment->user_id = user()->id;
            $comment->parent_id = $request->comment_id;
            $comment->comment = $request->comment;

            $setting = optional(option("blog"));

            if ($setting['comment_approve'] == 1) {
                $comment->status = 1;
                $msg = "Your comment is posted successfully!";
            } else {
                $comment->status = 0;
                $msg = "Success! Administrator will review your comment and approve soon.";
            }

            $comment->save();

            return response()->json([
                'status' => 1,
                'data' => $msg,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }

    public function favoriteComment(Request $request)
    {
        $id = $request->get('id');
        $like = $request->get('like');
        $comment = BlogComment::with('favorite_to_users')->where('id', $id)->whereStatus(1)->firstorfail();
        if (\Auth::check() == false) {
            Session::put('url.intended', route('blog.detail', $comment->post->slug . '#post_comment_form'));

            return response()->json(['status' => 0, 'data' => 'login']);
        }
        $isFavorite = in_array($comment->id, user()->favorite_to_comments->where('pivot.favorite', 1)->pluck("id")->toArray());
        $isunFavorite = in_array($comment->id, user()->favorite_to_comments->where('pivot.favorite', 0)->pluck("id")->toArray());

        $favorite_count = $comment->favorite_to_users->where('pivot.favorite', 1)->count();
        $unfavorite_count = $comment->favorite_to_users->where('pivot.favorite', 0)->count();

        if ($like == 'like') {
            if ($isFavorite) {
                \DB::table('blog_favorite_comment_user')->where('comment_id', $comment->id)
                    ->where('user_id', user()->id)
                    ->where('favorite', 1)
                    ->delete();
                $favorite_count--;
                $data = 1;
            } else {
                \DB::table('blog_favorite_comment_user')->insert([
                    'comment_id' => $comment->id,
                    'user_id' => user()->id,
                    'favorite' => 1,
                ]);
                $favorite_count++;
                if ($isunFavorite) {
                    \DB::table('blog_favorite_comment_user')->where('comment_id', $comment->id)
                        ->where('user_id', user()->id)
                        ->where('favorite', 0)
                        ->delete();
                    $unfavorite_count--;
                }
                $data = 2;
            }
        } else {
            if ($isunFavorite) {
                \DB::table('blog_favorite_comment_user')
                    ->where('comment_id', $comment->id)
                    ->where('user_id', user()->id)
                    ->where('favorite', false)
                    ->delete();
                $unfavorite_count--;
                $data = 3;
            } else {
                \DB::table('blog_favorite_comment_user')->insert([
                    'comment_id' => $comment->id,
                    'user_id' => user()->id,
                    'favorite' => false,
                ]);
                $unfavorite_count++;
                if ($isFavorite) {
                    $favorite_count--;
                    \DB::table('blog_favorite_comment_user')
                        ->where('comment_id', $comment->id)
                        ->where('user_id', user()->id)
                        ->where('favorite', 1)
                        ->delete();
                }
                $data = 4;
            }
        }

        return response()->json([
            'status' => 1,
            'data' => $data,
            'like_count' => $favorite_count,
            'dislike_count' => $unfavorite_count,
        ]);
    }

    public function package(Request $request)
    {
        $setting = optional(option("blog", null));
        if ($setting['permission'] != 'paid' && $setting['permission'] != 'both') {
            abort(404);
        }

        if ($request->ajax()) {
            $package = new BlogPackage();
            $result = $package->filterItem($request);

            return response()->json($result);
        }

        return view('frontend.blog.package');
    }

    public function packageDetail($slug)
    {
        $setting = optional(option("blog", null));
        if ($setting['permission'] != 'paid' && $setting['permission'] != 'both') {
            abort(404);
        }

        $package = new BlogPackage();
        $item = $package->where('slug', $slug)
            ->with('media', 'prices')
            ->status(1)
            ->firstorfail();

        return view('frontend.blog.packageDetail', compact('item'));
    }

    public function cartRule()
    {
        $rule['quantity'] = 'required|numeric|min:1';
        $rule['price'] = 'required';

        return $rule;
    }

    public function addToCart(Request $request, $id)
    {
        try {
            $setting = optional(option("blog", null));
            if ($setting['permission'] != 'paid' && $setting['permission'] != 'both') {
                abort(404);
            }

            $validation = Validator::make($request->all(), $this->cartRule());
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $package = new BlogPackage();

            $item = $package->whereId($id)
                ->whereStatus(1)
                ->firstOrFail();
            if ($request->price == 0) {
                $price = $item->standardPrice;
            } else {
                $price = $item->prices()
                    ->whereStatus(1)
                    ->whereId($request->price)
                    ->firstOrFail();
            }
            $oldCart = Session::get("cart");
            $cart = new Cart($oldCart);
            $cart->add($item, route('blog.package.detail', $item->slug), $request->quantity, $price->price, 'blogPackage', $item->getFirstMediaUrl("thumbnail"), $price->recurrent, $item->name, $price);

            Session::put("cart", $cart);

            return response()->json([
                'status' => 1,
                'data' => view('components.front.cart')->render(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => ['Item not found!'],
            ]);
        }
    }
}
