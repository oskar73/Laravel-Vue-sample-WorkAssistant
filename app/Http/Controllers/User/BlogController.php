<?php

namespace App\Http\Controllers\User;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\NotificationTemplate;
use App\Models\UserBlogPackage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class BlogController extends UserController
{
    public function __construct(BlogPost $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            return $this->model->getUserDataTable();
        }

        if (! user()->hasWebsiteBlogModule()) {
            return redirect()->route('user.dashboard');
        }

        return view(self::$viewDir . "blog.index");
    }
    public function create()
    {
        $setting = optional(option("blog"));
        if ($setting['permission'] == null || $setting['permission'] == 'not') {
            return back()->with("info", "Sorry we are not accepting any blog posts at this time.");
        }

        if ($setting['permission'] == 'paid') {
            $result = 0;
            $packages = UserBlogPackage::where("user_id", user()->id)->get();
            foreach ($packages as $package) {
                if ($package->post_number == -1 || ($package->post_number - $package->current_number) > 0) {
                    $result = 1;
                }
            }
            if ($result == 0) {
                return back()
                    ->with('linkToastr', "View Packages")
                    ->with("linkText", "We are accepting only paid blogging now.<br> You don\'t have active package at the moment. <br>Would you like to add a blog post? <br> Please click view package button to purchase available blog post package.<br>")
                    ->with('linkLink', route('blog.package'));
            }
        }
        $categories = BlogCategory::with('approvedTags')
            ->select('id', 'name')
            ->whereStatus(1)
            ->orderBy('order')
            ->get();

        $tags = BlogTag::whereStatus(1)
            ->orderBy('name')
            ->get();

        return view(self::$viewDir . "blog.create", compact("categories", "tags"));
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                $this->model->storeRule($request),
                $this->model::CUSTOM_VALIDATION_MESSAGE
            );

            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            $setting = optional(option("blog"));
            if ($setting['permission'] == null || $setting['permission'] == 'not') {
                return response()->json([
                    'status' => 0,
                    'data' => ["Sorry we are not accepting any blog posts at this time."],
                ]);
            }
            if ($setting['permission'] == 'paid') {
                $result = 0;
                $packages = UserBlogPackage::where("user_id", user()->id)->get();
                foreach ($packages as $package) {
                    if ($package->post_number == -1 || ($package->post_number - $package->current_number) > 0) {
                        $result = 1;
                    }
                }
                if ($result == 0) {
                    return $this->jsonError(["We are accepting only paid blogging now.You don\'t have active package at the moment."]);
                }
            }
            $item = $this->model;
            $item->user_id = user()->id;
            $item->category_id = $request->category;
            $item->title = $request->title;
            $item->body = $request->description;
            $item->is_published = $request->publish;
            $item->video = $request->video;
            if ($setting['post_approve'] == 1) {
                $item->status = 'approved';
            } else {
                $item->status = 'pending';
            }

            if ($setting['permission'] == 'paid' || $setting['permission'] == 'both') {
                $result = 0;
                $packages = UserBlogPackage::where("user_id", user()->id)->get();
                foreach ($packages as $package) {
                    if ($package->post_number == -1 || ($package->post_number - $package->current_number) > 0) {
                        $result = 1;
                    }
                }
                if ($result == 1) {
                    $package = UserBlogPackage::where(function ($query) {
                        $query->where('post_number', '==', -1);
                        $query->orWhereRaw("user_blog_packages.post_number>user_blog_packages.current_number");
                    })->where("user_id", user()->id)
                        ->first();

                    $package->increment("current_number");
                    $item->visible_date = Carbon::now()->toDateString();
                    $item->is_free = 0;
                    $item->purchase_id = $package->id;
                } else {
                    $item->is_free = 1;
                    $item->visible_date = $this->model->getVisibleDate(user()->id, $setting);
                    $item->purchase_id = null;
                }
            } else {
                $item->is_free = true;
                $item->visible_date = $this->model->getVisibleDate(auth()->user()->id, $setting);
                $item->purchase_id = null;
            }

            if ($request->links !== null) {
                $item->links = json_encode($request->links);
            } else {
                $item->links = null;
            }
            $item->gallery_order = $request->order;
            $item->save();

            $item->tags()->sync($request->tags);

            $item->addMediaFromBase64(json_decode($request->image)->output->image)
                ->usingFileName(guid() . ".jpg")
                ->toMediaCollection('image');

            foreach ($request->images ?? [] as $image) {
                $item->addMedia($image)
                    ->usingFileName(guid() . ".jpg")
                    ->toMediaCollection('images');
            }
            foreach ($request->videos ?? [] as $video) {
                $item->addMedia($video)
                    ->toMediaCollection('video');
            }

            if ($item->status == 'pending' && $item->is_published == 1) {
                $notification = new NotificationTemplate();
                $detail['url'] = route('admin.blog.post.show', $item->id);
                $detail['slug'] = $notification::BLOG_POST_APPROVAL;

                $notification->sendNotificationToAdmin($detail);
            }

            return response()->json([
                'status' => 1,
                'data' => $item,
            ]);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function edit($id)
    {
        $post = $this->model->where("user_id", user()->id)
            ->whereId($id)
            ->firstorfail();

        $categories = BlogCategory::with('approvedTags')
            ->select('id', 'name')
            ->whereStatus(1)
            ->orderBy('order')
            ->get();

        $tags = BlogTag::whereStatus(1)
            ->orderBy('name')
            ->get();

        return view(self::$viewDir . "blog.edit", compact("post", "categories", "tags"));
    }
    public function update(Request $request, $id)
    {
        try {
            $item = $this->model->where("user_id", user()->id)
                ->whereId($id)
                ->firstorfail();

            $validation = Validator::make(
                $request->all(),
                $this->model->userUpdateRule($request),
                $this->model::CUSTOM_VALIDATION_MESSAGE
            );
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }

            $setting = optional(option("blog"));

            $item->category_id = $request->category;
            $item->title = $request->title;
            $item->body = $request->description;
            $item->is_published = $request->publish;
            if ($setting['post_approve'] == 1) {
                $item->status = 'approved';
            } else {
                $item->status = 'pending';
            }

            if ($request->links !== null) {
                $item->links = json_encode($request->links);
            } else {
                $item->links = null;
            }
            $item->gallery_order = $request->order;
            $item->video = $request->video;

            $item->save();
            $item->tags()->sync($request->tags);

            $ids = $request->oldItems ?? [];
            if ($request->image !== null) {
                $collections = ['images', 'video', 'image'];
            } else {
                $collections = ['images', 'video'];
            }

            $item->media()
                ->whereNotIn("id", $ids)
                ->whereIn("collection_name", $collections)
                ->get()
                ->each
                ->delete();

            if ($request->image) {
                $item->addMediaFromBase64(json_decode($request->image)->output->image)
                    ->usingFileName(guid() . ".jpg")
                    ->toMediaCollection('image');
            }
            foreach ($request->images ?? [] as $image) {
                $item->addMedia($image)
                    ->usingFileName(guid() . ".jpg")
                    ->toMediaCollection('images');
            }
            foreach ($request->videos ?? [] as $video) {
                $item->addMedia($video)
                    ->toMediaCollection('video');
            }

            if ($item->status == 'pending' && $item->is_published == 1) {
                $notification = new NotificationTemplate();
                $datail['url'] = route('admin.blog.post.show', $item->id);
                $datail['slug'] = $notification::BLOG_POST_APPROVAL;

                $notification->sendNotificationToAdmin($datail);
            }
            if ($item->status == 'approved') {
                $msg = "Successfully created! Now your posting is live.";
            } else {
                $msg = "Successfully created! Admin will check your blog post and approve soon.";
            }

            return response()->json([
                'status' => 1,
                'data' => $msg,
            ]);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
    public function detail($id)
    {
        $post = $this->model->with('media', 'tags', 'category')->whereId($id)->where("user_id", user()->id)->firstorfail();

        return view(self::$viewDir . "blog.detail", compact("post"));
    }
    public function switchPost(Request $request)
    {
        try {
            $action = $request->action;
            $notification = new NotificationTemplate();
            $slug = '';
            $notify = 0;

            $posts = $this->model->with('user')
                ->whereIn('id', $request->ids)
                ->get();

            if ($action === 'approve') {
                $posts->each->update(['status' => 'approved', 'denied_reason' => $request->denied_reason]);
                $slug = $notification::BLOG_POST_APPROVED;
                $notify = 1;
            } elseif ($action === 'deny') {
                $posts->each->update(['status' => 'denied', 'denied_reason' => $request->denied_reason]);
                $data['reason'] = $request->denied_reason;
                $slug = $notification::BLOG_POST_DENIED;
                $notify = 1;
            } elseif ($action === 'featured') {
                $posts->each->update(['featured' => 1]);
            } elseif ($action === 'unfeatured') {
                $posts->each->update(['featured' => 0]);
            } elseif ($action === 'publish') {
                $posts->each->update(['is_published' => 1]);
            } elseif ($action === 'draft') {
                $posts->each->update(['is_published' => 0]);
            } elseif ($action === 'delete') {
                $posts->each->delete();
            }

            if ($notify == 1) {
                foreach ($posts as $post) {
                    $data['url'] = route('user.blog.detail', $post->id);
                    $data['username'] = $post->user->name;
                    $notification->sendNotification($data, $slug, $post->user);
                }
            }

            return $this->jsonSuccess($action);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
}
