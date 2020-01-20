<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\EmailCategory;
use App\Models\Logo\Video;
use App\Models\Logo\VideoCategory;
use App\Models\NewsletterAdsListing;
use App\Models\NotificationTemplate;
use App\Models\Social\Account as SocialAccount;
use App\Models\Subscriber;
use App\Models\User;
use App\Models\Newsletter;
use App\Models\NewsletterTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Intervention\Image\Facades\Image;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return mixed
     */
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');
    }

    public function profile($role)
    {
        if (!in_array($role, ['admin', 'account'])) {
            abort(404);
        }

        $socialAccount = SocialAccount::where('username', user()->username)->orWhere('email', user()->email)->first();

        return view('account.profile', compact('socialAccount'));
    }

    public function setting()
    {
        return view('account.setting');
    }

    public function settingUpdate(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email,' . $request->user_id,
                'first_name' => 'required|string|max:45',
                'last_name' => 'required|string|max:45',
                'date_of_birth' => 'required|date_format:Y-m-d|before:today',
                'phone' => 'required|min:5|max:15',
                'address1' => 'required|max:191',
                'address2' => 'nullable|max:191',
                'city' => 'required|max:191',
                'state' => 'required|max:191',
                'zipcode' => 'required|postal_code:' . $request->country,
                'country' => 'required|exists:mysql2.country,iso',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation);
            }

            user()->first_name = $request->first_name;
            user()->last_name = $request->last_name;
            user()->birthday = $request->date_of_birth;
            user()->phone_no = $request->phone;

            $address = new \stdClass();
            $address->address1 = $request->address1;
            $address->address2 = $request->address2;
            $address->city = $request->city;
            $address->state = $request->state;
            $address->zipcode = $request->zipcode;
            $address->country = $request->country;
            user()->address = json_encode($address);

            user()->timezone = $request->timezone;
            user()->timeformat = $request->timeformat;
            user()->save();

            $data['url'] = route('account.setting.update');
            $data['username'] = user()->first_name . '' . user()->last_name;

            $notification = new NotificationTemplate();
            $notification->sendNotification($data, $notification::PROFILE_CHANGED, user());

            return redirect()->route('account.setting')->with('success', 'Account information updated successfully..!');
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function subscribedSwitch(Request $request)
    {
        $id = $request->get('id');
        $type = $request->get('type');

        if ($type == 'category') {
            $category = BlogCategory::find($id);
            user()->unsubscribe($category);
        } elseif ($type == 'post') {
            $post = BlogPost::find($id);
            user()->unsubscribe($post);
        } elseif ($type == 'author') {
            $author = User::find($id);
            user()->unfollow($author);
        }

        return $this->jsonSuccess();
    }

    public function subscribedUpdate(Request $request)
    {
        if ($request->status) {
            user()->addSubscriber()
                ->categories()
                ->sync($request->categories);
        } else {
            user()->removeSubscriber();
        }

        return $this->jsonSuccess();
    }

    public function subscribed($role)
    {
        if (!in_array($role, ['admin', 'account'])) {
            abort(404);
        }

        if (request()->wantsJson()) {
            $categories = user()->subscribedCats;
            $posts = user()->subscribedPosts;
            $authors = user()->followings()->select("users.id", DB::raw("CONCAT(first_name, ' ', last_name)"), "users.username")->get();

            $view = view("components.account.subscribed", compact("categories", "posts", "authors"))->render();

            return $this->jsonSuccess($view);
        }

        $subscriber = Subscriber::where("email", user()->email)->first();
        $unsubscribeds = [];
        if ($subscriber) {
            $unsubscribeds = $subscriber->categories()->pluck("email_categories.id")->toArray();
        }
        $categories = EmailCategory::status(1)->get(['id', 'name']);

        return view('account.subscribed', compact("subscriber", "categories", "unsubscribeds"));
    }

    public function profileUpdate(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), user()->profileUpdateRule($request));
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }

            $user = user()->updateProfile($request);

            $data['url'] = route('profile', user()->hasRole("admin") ? 'admin' : 'account');
            $data['username'] = user()->name;

            $notification = new NotificationTemplate();
            $notification->sendNotification($data, $notification::PROFILE_CHANGED, $user);

            return $this->jsonSuccess($user);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (user()->password !== null && !Hash::check($value, user()->password)) {
                        $fail('Old Password didn\'t match');
                    }
                },
            ],
            'new_password' => [
                'required',
                'different:old_password',
                'max:191',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'confirm_password' => 'required|same:new_password',
        ]);

        try {
            $user = user()->updatePsw($request);

            $data['url'] = route('profile', user()->hasRole("admin") ? 'admin' : 'account') . "#/password";
            $data['username'] = user()->name;

            $notification = new NotificationTemplate();
            $notification->sendNotification($data, $notification::PROFILE_CHANGED, $user);

            $view = '
            <div class="container my-5">
                <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-success">
                    <div class="m-alert__icon">
                        <i class="flaticon-exclamation-1"></i>
                        <span></span>
                    </div>
                    <div class="m-alert__text">
                        <strong>Success! For security purposes, you were logged out. Please <a href="' . route('login') . '">login</a> with your new password.</strong>
                    </div>
                </div>
            </div>
            ';
            if ($request->ajax()) {
                return response()->json([
                    'success' => 'Password updated successfully..!',
                    'status' => 1,
                    'data' => $user,
                    'redirect_url' => route('user.getting.started.welcome'),
                ]);
            }

            return redirect()->route('user.getting.started.welcome')->with('success', 'Password updated successfully..!');
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function uploadImage(Request $request, $folder = null)
    {
        try {
            $validation = Validator::make($request->all(), [
                'file' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
            ]);

            if ($validation->fails()) {
                abort(404);
            }

            $file = $request->file('file');
            $name = guid() . '.' . $file->getClientOriginalExtension();

            $folder_name = config("custom.storage_name.tinymce");
            $path = $folder ? $folder_name . "/" . $folder : $folder_name;

            return response()->json([
                'location' => BaseModel::fileUpload($file, $name, $path),
            ]);
        } catch (\Exception $e) {
            echo json_encode($e->getMessage());
        }
    }

    public function uploadImages(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), [
                'file' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
            ]);
            if ($validation->fails()) {
                abort(404);
            }

            $file = $request->file('file');

            $name = $file->getClientOriginalName();

            $folder_name = config("custom.storage_name.email");

            $path = $folder_name . "/" . $id;

            BaseModel::fileUpload($file, $name, $path);

            return response()->json([
                'name' => $name,
                'original_name' => $name,
            ]);
        } catch (\Exception $e) {
            echo json_encode($e->getMessage());
        }
    }

    public function notifications($role)
    {
        if (!in_array($role, ['admin', 'account'])) {
            abort(404);
        }

        if (request()->wantsJson()) {
            return user()->getNotifications(request()->get("status"));
        }

        return view("account.notification");
    }

    public function notificationDetail($role, $id)
    {
        if (!in_array($role, ['admin', 'account'])) {
            abort(404);
        }
        $notification = user()->notifications()->whereId($id)->firstorfail();
        if ($notification->read_at == null) {
            $notification->read_at = Carbon::now()->toDateTimeString();
            $notification->save();
        }

        return view("account.notificationDetail", compact("notification"));
    }

    public function readAllNotifications($role, $status)
    {
        if (!in_array($role, ['admin', 'account'])) {
            abort(404);
        }
        $query = user()->notifications($status);
        if ($status == 'unread') {
            $query = $query->where('read_at', null);
        }
        $notifications = $query->get();
        foreach ($notifications as $notification) {
            if ($notification->read_at == null) {
                $notification->read_at = Carbon::now()->toDateTimeString();
                $notification->save();
            }
        }

        return redirect()->back()->with(['success' => "All Notifications have been marked as read!"]);
    }

    public function notificationSwitch(Request $request)
    {
        try {
            $action = $request->action;

            if ($action === 'read') {
                user()->notifications()->whereIn('id', $request->ids)->get()->each->update(['read_at' => Carbon::now()->toDateTimeString()]);
            } elseif ($action === 'unread') {
                user()->notifications()->whereIn('id', $request->ids)->get()->each->update(['read_at' => null]);
            } elseif ($action === 'delete') {
                user()->notifications()->whereIn('id', $request->ids)->get()->each->delete();
            }

            return response()->json(['status' => 1]);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function notificationCheck(Request $request)
    {
        $lastUnread = user()->unreadNotifications->first()->created_at;
        option([user()->email . 'last-notification-check-time' => Carbon($lastUnread)->format('Y-m-d H:i:s')]);

        return response()->json(['status' => 0]);
    }

    public function checkFileExistence(Request $request)
    {
        $path = $request->get('path');
        if (file_exists(public_path($path))) {
            return $this->jsonSuccess();
        }

        return $this->jsonError();
    }

    public function videos()
    {
        $categories = VideoCategory::with(['subcategories' => function ($query) {
            $query->with('items');
        }, 'items'])->get();

        $videos = Video::where('status', 1)->get();

        return view('frontend.videos', compact('categories', 'videos'));
    }

    public function template()
    {
        $fileContent = app()->environment('production') ? file_get_contents(asset('vendor/mosaico/template/index.html')) : file_get_contents(public_path('vendor/mosaico/template/index.html'));

        return response($fileContent)
            ->header('Content-Type', 'text/html');
    }

    public function generateNewsletterImage(Request $request)
    {
        $method = $request->query('method');
        $params = explode(',', $request->query('params', '100,100')); // Default size
        $text = $request->query('text', $params[0] . ' x ' . $params[1]);
        $w = intval($params[0]);
        $h = intval($params[1]);

        if ($method == 'placeholder' || $method == 'placeholder2') {
            $size = $method == 'placeholder2' ? 80 : 40;
            $image = Image::canvas($w, $h, '#808080');

            // Create placeholder pattern (simplified for brevity)
            // This part needs custom implementation based on your specific pattern requirements

            // Add text
            $image->text($text, $w / 2, $h / 2, function ($font) use ($size) {
//                $font->file(public_path('assets/fonts/noto-sans-400-normal.ttf')); // Specify your font path
                $font->size($size);
                $font->color('#ffffff');
                $font->align('center');
                $font->valign('middle');
            });

            return $image->response('png');
        } // Resize/Crop logic (simplified example)
        elseif ($method == 'resize') {
            $src = $request->query('src', ''); // Assuming you have validation for this
            $image = Image::make($src);

            if ($params[0] === 'null') {
                $image->resize(null, $h, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } elseif ($params[1] === 'null') {
                $image->resize($w, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $image->resize($w, $h);
            }

            return $image->response('png');
        }

        // Add more conditions for other methods like 'cover', etc.

        return response()->json(['error' => 'Method not supported'], 400);
    }

    public function uploadNewsletterImage(Request $request)
    {
        $files = $request->file('files');
        if (count($files)) {
            $file = $files[0];
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = '/uploads/' . $fileName;
            \Storage::disk('s3-pub-bizinabox')->put($path, file_get_contents($file));

            return response()->json([
                'files' => [
                    [
                        'name' => $fileName,
                        'url' => \Storage::disk('s3-pub-bizinabox')->url($path),
                        'thumbnailUrl' => route('newsletter.image', [
                            'src' => \Storage::disk('s3-pub-bizinabox')->url($path),
                            'method' => 'resize',
                            'params' => '90x90',
                        ]),
                    ],
                ],
            ]);
        }
    }

    public function newsletterItemPreview($slug)
    {
        $item = Newsletter::where('slug', $slug)->firstorfail();

        if (!$item->html) {
            return back()->with('error', 'Item content is empty');
        }

        return response($item->html)->header('Content-Type', 'text/html');
    }

    public function newsletterTemplatePreview($slug)
    {
        $template = NewsletterTemplate::where('slug', $slug)->firstorfail();

        if (!$template->html) {
            return back()->with('error', 'Template is empty');
        }

        return response($template->html)->header('Content-Type', 'text/html');
    }

    public function impClick(Request $request, $id)
    {
        try {
            Session::put("newsletterAds-impclick-" . $id, true);

            $listing = NewsletterAdsListing::where('status', "approved")
                ->where('id', $id)
                ->firstorfail();
            $listing->current_number++;
            $listing->save();

            $listing->track($request);

            if ($listing->price->type == 'impression') {
                if ($listing->current_number >= $listing->impression_number) {
                    $listing->status = 'expired';
                    $listing->save();

                    $event = $listing->events()->first();
                    if ($event != null) {
                        $event->end_date = Carbon::now()->toDateString();
                        $event->save();
                    }
                }
            }

            return redirect($listing->url);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
