<?php

namespace App\Models;

use App\Models\Community\Account as CommunityAccount;
use App\Models\GraphicDesign\UserDesign;
use App\Models\Social\Account as SocialAccount;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Overtrue\LaravelFavorite\Traits\Favoriter;
use Overtrue\LaravelFollow\Traits\Followable;
use Overtrue\LaravelFollow\Traits\Follower;
use Overtrue\LaravelSubscribe\Traits\Subscriber;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

use Spatie\Permission\Traits\HasRoles;
use Yajra\DataTables\Facades\DataTables;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use Notifiable;
    use HasRoles;
    use InteractsWithMedia;
    use Followable;
    use Favoriter;
    use Subscriber;
    use Follower;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ["id", "created_at", "updated_at"];

    protected $connection = 'mysql';
    protected $table = 'users';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $appends = ['image', 'name'];

    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl("avatar");
    }

    public function getNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public function designs(): HasMany
    {
        return $this->hasMany(UserDesign::class)->with('preview');
    }

    public function storeRule()
    {
        $rule['pin_number'] = 'required|string|min:4|max:7';
        $rule['email'] = 'required|email|unique:users,email';
        $rule['username'] = 'required|string|min:4|max:45|unique:users,username';
        $rule['roles.*'] = 'nullable|in:admin,client,employee';
        $rule['password'] = 'required|min:8|max:191';
        $rule['status'] = 'required|in:active,inactive';

        return $rule;
    }

    public static function getUsername($email)
    {
        $username = explode('@', $email)[0] ?? guid();
        $check = User::where("username", $username)->count();
        if ($check) {
            return guid();
        }

        return $username;
    }

    public static function getPinNumber()
    {
        do {
            $pin = mt_rand(1000000, 9999999);
            $check = User::where("pin_number", $pin)->count();
        } while ($check > 0);

        return $pin;
    }

    public function storeItem($request)
    {
        $user = $this;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->pin_number = $request->pin_number;
        $user->email_verified_at = $request->verified ? Carbon::now()->toDateTimeString() : null;
        $user->password = bcrypt($request->password);
        $user->status = $request->status;
        $user->save();

        if ($request->origin_image) {
            $user->addMedia($request->origin_image)
                ->usingFileName(guid() . "." . $request->origin_image->getClientOriginalExtension())
                ->toMediaCollection('avatar');
        } elseif ($request->image) {
            // $user->addMediaFromBase64($request->image)
            //     ->usingFileName(guid() . ".jpg")
            //     ->toMediaCollection('avatar');

            $user->addMediaFromBase64(json_decode($request->image)->output->image)
                ->usingFileName(guid() . ".jpg")
                ->toMediaCollection('avatar');
        }

        if ($request->subscribe) {
            $user->addSubscriber();
        }

        return $user;
    }

    public function profileUpdateRule($request, $id = null)
    {
        if ($id == null) {
            $id = user()->id;
        } else {
            $rule['username'] = 'required|string|max:45|unique:users,username,' . $id;
            $rule['roles.*'] = 'nullable|in:admin,client,employee';
            $rule['status'] = 'required|in:active,inactive';
        }
        $rule['first_name'] = 'required|max:45';
        $rule['last_name'] = 'required|max:45';
        $rule['email'] = 'required|email|unique:users,email,' . $id;

        return $rule;
    }

    public function updateProfile($request, $admin = 0)
    {
        $user = $this;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->introduction = $request->introduction;
        if ($request->timezone) {
            $user->timezone = $request->timezone;
        }
        if ($request->time_format) {
            $user->timeformat = $request->time_format;
        }

        if ($request->birthday) {
            $user->birthday = $request->birthday;
        }
        if ($request->phone) {
            $user->phone_no = $request->phone;
        }
        if ($admin == 1) {
            $user->username = $request->username;
            $user->status = $request->status;
            if ($request->verified && $this->email_verified_at == null) {
                $user->email_verified_at = Carbon::now()->toDateTimeString();
            } elseif ($request->verified == null && $this->email_verified_at != null) {
                $user->email_verified_at = null;
            }
        }
        $user->save();
        if ($request->image) {
            $user->clearMediaCollection('avatar')
                ->addMediaFromBase64(json_decode($request->image)->output->image)
                ->usingFileName(guid() . ".jpg")
                ->toMediaCollection('avatar');
        }

        $socialRole = '0';
        $communityRole = 1;
        foreach ($user->roles as $roleData) {
            if ($roleData->name === 'admin') {
                $socialRole = '1';
                $communityRole = 3;

                break;
            } elseif ($roleData->name === 'developer') {
                $socialRole = '2';
                $communityRole = 2;
            }
        }

        $socialAccount = $user->socialAccount;
        if (! $socialAccount) {
            $socialAccount = new SocialAccount();
        }
        $socialAccount->email = $user->email;
        $socialAccount->first_name = $user->first_name;
        $socialAccount->last_name = $user->last_name;
        $socialAccount->username = $user->username;
        $socialAccount->password = $user->password;
        $socialAccount->avatar = $user->image ? $user->image : 'upload/photos/d-avatar.jpg';
        $socialAccount->address = $user->address ?? '';
        $socialAccount->birthday = $user->birthday ?? '0000-00-00';
        $socialAccount->admin = $socialRole;
        $socialAccount->phone_number = $user->phone_no ?? '';
        $socialAccount->status = $user->status == 'active' ? '1' : '0';
        $socialAccount->verified = is_null($user->email_verified_at) ? '0' : '1';
        $socialAccount->active = $user->status == 'active' ? '1' : '0';
        $socialAccount->src = 'site';

        if ($request->facebook) {
            $socialAccount->facebook = $request->facebook;
        }
        if ($request->twitter) {
            $socialAccount->twitter = $request->twitter;
        }
        if ($request->linkedin) {
            $socialAccount->linkedin = $request->linkedin;
        }
        if ($request->youtube) {
            $socialAccount->youtube = $request->youtube;
        }
        if ($request->vk) {
            $socialAccount->vk = $request->vk;
        }
        if ($request->instagram) {
            $socialAccount->instagram = $request->instagram;
        }

        if ($request->gender) {
            $socialAccount->gender = $request->gender;
        }
        $socialAccount->save();

        $communityAccount = $user->communityAccount;
        if (! $communityAccount) {
            $communityAccount = new CommunityAccount();
        }
        $communityAccount->email = $user->email;
        $communityAccount->name = $user->first_name . ' ' . $user->last_name;
        $communityAccount->username = $user->username;
        $communityAccount->password = $user->password;
        $communityAccount->avatar = $user->image;
        $communityAccount->type = $communityRole;
        $communityAccount->email_verified_at = is_null($user->email_verified_at) ? null : new \DateTime($user->email_verified_at);
        $communityAccount->save();

        return $user;
    }

    public function updateAccountSetting($request)
    {
        $user = $this;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->address = $request->address;
        $user->phone_no = $request->phone_no;
        $user->save();

        return $user;
    }

    public function updatePsw($request)
    {
        $user = $this;
        $user->password = bcrypt($request->new_password);
        $user->password_update = 0;
        $user->save();

        return $user;
    }

    public function addSubscriber($status = 1)
    {
        $subscriber = \App\Models\Subscriber::where("email", $this->email)->first();
        if ($subscriber == null) {
            $subscriber = new \App\Models\Subscriber();
            $subscriber->token = \Str::random(64);
            $subscriber->email = $this->email;
        }
        $subscriber->status = 1;
        $subscriber->save();

        return $subscriber;
    }

    public function removeSubscriber()
    {
        $subscriber = \App\Models\Subscriber::where("email", $this->email)->first();
        if ($subscriber != null) {
            $subscriber->delete();
        }

        return true;
    }

    public function sendEmailVerificationNotification()
    {
        $data['url'] = URL::temporarySignedRoute(
            'verification.verify',
            \Illuminate\Support\Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $this->id,
                'hash' => sha1($this->getEmailForVerification()),
            ]
        );
        $notification = new NotificationTemplate();
        $notification->sendEmail($data, $notification::EMAIL_VERIFICATION, $this->email);
    }

    public function sendPasswordResetNotification($token)
    {
        $data['username'] = $this->name;
        $data['url'] = route('password.reset', $token);

        $notification = new NotificationTemplate();
        $notification->sendEmail($data, $notification::FORGOT_PASSWORD, $this->email);
    }

    public function avatar()
    {
        $avatar = $this->getFirstMediaUrl('avatar');
        if ($avatar != "" || $avatar != null) {
            return $avatar;
        } else {
            $name = $this->name ?? 'New User';

            return "https://ui-avatars.com/api/?size=300&&name=" . $name;
        }
    }

    public function isPasswordForceUpdateNeed()
    {
        if ($this->password_update == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function isGettingStarted(): bool
    {
        if ($this->started == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getCompletedPercentage()
    {
        $result = 0;
        $address = json_decode($this->address);
        if ($this->pin_number) {
            $result++;
        }
        if ($this->birthday) {
            $result++;
        }
        if ($this->username) {
            $result++;
        }

        if ($address?->address1) {
            $result++;
        }
        if ($address?->city) {
            $result++;
        }
        if ($address?->state) {
            $result++;
        }
        if ($address?->zipcode) {
            $result++;
        }
        if ($address?->country) {
            $result++;
        }

        return number_format($result / 8 * 100, '0', '.', '');
    }

    public function isEmailVerified()
    {
        if ($this->email_verified_at) {
            return true;
        } else {
            return false;
        }
    }

    public function makeEmailVerified()
    {
        $this->email_verified_at = now()->toDateTimeString();
        $this->save();
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'user_id')->withDefault();
    }

    public function getAddress1Attribute()
    {
        return json_decode($this->address)->address1 ?? null;
    }

    public function domainContacts()
    {
        return $this->hasMany(DomainContact::class, 'user_id');
    }

    public function domainConnects()
    {
        return $this->hasMany(DomainConnect::class, 'user_id');
    }

    public function domainCustoms()
    {
        return $this->hasMany(DomainCustom::class, 'user_id');
    }

    public function domains()
    {
        return $this->hasMany(Domain::class, 'user_id');
    }

    public function logins()
    {
        return $this->hasMany(UserLogin::class, 'user_id');
    }

    // all web sties
    public function websites()
    {
        return $this->hasMany(Website::class, 'user_id');
    }

    public function readymades()
    {
        return $this->hasMany(UserPackage::class, 'user_id')->where("package", 0);
    }

    public function packages()
    {
        return $this->hasMany(UserPackage::class, 'user_id')->where("package", 1);
    }

    public function websitePackages()
    {
        return $this->hasMany(UserPackage::class, 'user_id');
    }

    public function todos()
    {
        return $this->hasMany(Todo::class, 'user_id');
    }

    public function plugins()
    {
        return $this->hasMany(UserPlugin::class, 'user_id');
    }

    public function services()
    {
        return $this->hasMany(UserService::class, 'user_id');
    }

    public function lacartes()
    {
        return $this->hasMany(UserLacarte::class, 'user_id');
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function posts()
    {
        return $this->hasMany(BlogPost::class, 'user_id');
    }

    public function visiblePosts()
    {
        return $this->hasMany(BlogPost::class, 'user_id')->frontVisible()->withCount('favoriters');
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'user_id');
    }

    public function approvedComments()
    {
        return $this->hasMany(BlogComment::class, 'user_id')->where("status", 1);
    }

    public function favorite_to_comments()
    {
        return $this->belongsToMany(BlogComment::class, 'blog_favorite_comment_user', 'user_id', 'comment_id')->withPivot('favorite');
    }

    public function blogAdsListings()
    {
        return $this->hasMany(BlogAdsListing::class, 'user_id');
    }

    public function newsletterAdsListings()
    {
        return $this->hasMany(NewsletterAdsListing::class, 'user_id');
    }

    public function subscribedCats()
    {
        return $this->morphedByMany(BlogCategory::class, "subscribable", config('subscribe.subscriptions_table'))->select("blog_categories.id", "blog_categories.name", "blog_categories.slug");
    }

    public function subscribedPosts()
    {
        return $this->morphedByMany(BlogPost::class, "subscribable", config('subscribe.subscriptions_table'))->select("blog_posts.id", "blog_posts.title", "blog_posts.slug");
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function subscriptionOrderItems()
    {
        return $this->hasMany(OrderItem::class, 'user_id')->where("recurrent", 1);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id')->where("parent_id", 0);
    }

    public function appointments()
    {
        return $this->hasMany(AppointmentList::class, 'user_id');
    }

    public function purchaseFollowups()
    {
        return $this->hasMany(UserForm::class, 'user_id');
    }

    public function blogPackages()
    {
        return $this->hasMany(UserBlogPackage::class, 'user_id');
    }

    public function directoryListings()
    {
        return $this->hasMany(DirectoryListing::class, 'user_id');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class, 'user_id');
    }

    public function saveLoginActivity()
    {
        return $this->logins()->create([
            'ip' => request()->ip(),
            'device' => getOS(),
        ]);
    }

    public function myUnreadFromUsers()
    {
        return $this->hasOne(MessageCount::class, "from_id")
            ->where("from_type", "user")
            ->where("user_id", user()->id);
    }

    public function getLastMessage()
    {
        $id = $this->id;

        return Message::where(function ($query) use ($id) {
            $query->where("from_id", $id);
            $query->where("from_type", "user");
        })
            ->orWhere(function ($query) use ($id) {
                $query->where("to_id", $id);
                $query->where("to_type", "user");
            })->latest()->first();
    }

    public function getUnreads($type, $id)
    {
        $row = MessageCount::where("user_id", user()->id)
            ->where("from_id", $id)
            ->where("from_type", $type)
            ->first();

        return $row->count ?? 0;
    }

    public function getTranscriptIdAttribute()
    {
        if ($this->hasRole('admin')) {
            return $this->id;
        }
        $guest = Guest::where('email', $this->email)->latest()->first();
        if ($guest) {
            return $guest->id;
        }

        return 0;
    }

    public function getPurchaseUser($payeremail)
    {
        $new = 0;
        if (Auth::check()) {
            $authuser = Auth::user();
        } else {
            $authemail = $payeremail;
            $authuser = $this->where('email', $authemail)->first();

            if ($authuser == null) {
                $password = uniqid();

                $authuser = new User();
                $authuser->email = $payeremail;
                $authuser->username = self::getUsername($payeremail);
                $authuser->pin_number = self::getPinNumber();
                $authuser->status = "active";
                $authuser->password = bcrypt($password);
                $authuser->email_verified_at = now()->toDateTimeString();
                $authuser->save();

                $socialAccount = new SocialAccount();
                $socialAccount->email = $authuser->email;
                $socialAccount->first_name = '';
                $socialAccount->last_name = '';
                $socialAccount->username = $authuser->username;
                $socialAccount->password = $authuser->password;
                $socialAccount->avatar = $authuser->image ? $authuser->image : 'upload/photos/d-avatar.jpg';
                $socialAccount->address = '';
                $socialAccount->birthday = '0000-00-00';
                $socialAccount->admin = '0';
                $socialAccount->phone_number = '';
                $socialAccount->status = '1';
                $socialAccount->verified = '1';
                $socialAccount->active = '1';
                $socialAccount->src = 'site';
                $socialAccount->save();

                $communityAccount = new CommunityAccount();
                $communityAccount->email = $authuser->email;
                $communityAccount->name = '';
                $communityAccount->username = $authuser->username;
                $communityAccount->password = $authuser->password;
                $communityAccount->avatar = $authuser->image;
                $communityAccount->type = 1;
                $communityAccount->email_verified_at = new \DateTime($authuser->email_verified_at);
                $communityAccount->save();

                $authuser->raw_password = $password;
                $new = 1;

                session()->put("email_register", $payeremail);
            }
        }
        $result['authuser'] = $authuser;
        $result['new'] = $new;

        return $result;
    }

    public function totalTodo()
    {
        return Todo::my()
            ->selectRaw("type, sum(abs(count)) as count")
            ->groupBy("type")
            ->get()
            ->sum("count");
    }

    public function notifyNewUser()
    {
        $data['password'] = $this->raw_password;
        $data['url'] = route("register.password");

        $notification = new NotificationTemplate();
        $notification->sendEmail($data, 'new_account_by_payment', $this->email);
    }

    public function getDatatable($status)
    {
        switch ($status) {
            case 'all':
                $users = $this::with('roles', 'media')->where("id", "!=", user()->id);

                break;
            case 'active':
                $users = $this::with('roles', 'media')->where("id", "!=", user()->id)->where('status', "active");

                break;
            case 'inactive':
                $users = $this::with('roles', 'media')->where("id", "!=", user()->id)->where('status', "inactive");

                break;
        }
        if (isset(request()->filter_status)) {
            $filter_status = intval(request()->filter_status) ? 'active' : 'inactive';
            $users = $this::with('roles', 'media')->where("id", "!=", user()->id)->where('status', $filter_status);
        }
        if (request()->name) {
            $users = $users->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE', "%" . request()->name . "%");
        }
        if (request()->username) {
            $users = $users->where('username', 'LIKE', "%" . request()->username . "%");
        }
        if (request()->email) {
            $users = $users->where('email', request()->email);
        }
        if (request()->pin) {
            $users = $users->where('pin_number', request()->pin);
        }
        if (request()->role) {
            $users = $users->get()->filter(
                fn ($user) => $user->roles->where('id', request()->role)->toArray()
            );
        }

        return Datatables::of($users)->addColumn('checkbox', function ($row) {
            return '<input type="checkbox" class="checkbox" data-id="' . $row->id . '">';
        })->addColumn('role', function ($row) {
            $result = '';
            if ($row->hasRole("admin")) {
                $result .= "<img src='https://ui-avatars.com/api/?size=30&&name=A' title='Admin' class='rounded-circle m-1'>";
            }
            if ($row->hasRole("client")) {
                $result .= "<img src='https://ui-avatars.com/api/?size=30&&name=C' title='Client' class='rounded-circle m-1'>";
            }
            if ($row->hasRole("employee")) {
                $result .= "<img src='https://ui-avatars.com/api/?size=30&&name=E' title='Employee' class='rounded-circle m-1'>";
            }

            return $result;
        })->editColumn('status', function ($row) {
            if ($row->status == 'active') {
                return '<span class="c-badge c-badge-success hover-handle">Active</span><a href="javascript:void(0);" class="h-cursor c-badge c-badge-danger d-none origin-none down-handle hover-box switchOne" data-action="inactive">Inactive?</a>';
            } else {
                return '<span class="c-badge c-badge-danger hover-handle" >Inactive</span><a href="javascript:void(0);" class="h-cursor c-badge c-badge-success d-none origin-none down-handle hover-box switchOne" data-action="active">Active?</a>';
            }
        })->editColumn('created_at', function ($row) {
            return $row->created_at?->diffForHumans();
        })->addColumn('avatar', function ($row) {
            return "<img src='" . $row->avatar() . "' title='" . $row->name . "' class='user-avatar-50'>";
        })->editColumn('username', function ($row) {
            return "@" . $row->username;
        })->editColumn('email', function ($row) {
            if ($row->email_verified_at == null) {
                return " <i class=\"far fa-check-circle text-danger\" title='non-verified'> {$row->email}</i> <br><a href='#' class='c-badge c-badge-success hover-none send_verification' data-action='send_verification'>Send Verification Email</a>";
            } else {
                return "<i class=\"far fa-check-circle text-success\" title='Verified'></i> {$row->email}";
            }
        })->addColumn('action', function ($row) {
            return '<a href="' . route('admin.userManage.detail', $row->id) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-success m-btn--icon m-btn--icon-only m-btn--pill" title="Detail">
                       <i class="la la-eye"></i>
					</a>
					<a href="' . route('admin.userManage.edit', $row->id) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-success m-btn--icon m-btn--icon-only m-btn--pill" title="Edit">
                       <i class="la la-edit"></i>
					</a>
					<a href="' . route('admin.userManage.delete', $row->id) . '" data-action="user.delete" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete">
                       <i class="la la-trash-o"></i>
					</a>';
        })->rawColumns(['checkbox', 'status', 'role', 'avatar', 'email', 'action'])->make(true);
    }

    public function getNotifications($status)
    {
        if ($status == 'unread') {
            $notifications = user()->unreadNotifications();
        } else {
            $notifications = user()->notifications();
        }

        return Datatables::of($notifications)->addColumn('checkbox', function ($row) {
            return '<input type="checkbox" class="checkbox" data-id="' . $row->id . '">';
        })->addColumn('subject', function ($row) {
            return $row->data['subject'];
        })->addColumn('link', function ($row) {
            return "<a href='" . $row->data['url'] . "' class='btn btn-outline-success btn-sm'>Action</a>";
        })->editColumn('created_at', function ($row) {
            return $row->created_at->toDateTimeString();
        })->editColumn('read_at', function ($row) {
            return $row->read_at ? $row->read_at->toDateTimeString() : '';
        })->addColumn('action', function ($row) {
            return '<a href="' . route('notification.detail', ['id' => $row->id, 'role' => request()->is('admin/*') ? 'admin' : 'account']) . '" class="btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon" data-action="detail">
                        <span>
                            <i class="la la-eye"></i>
                            <span>Detail</span>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm m-1	p-2 m-btn m-btn--icon switchOne" data-action="delete">
                        <span>
                            <i class="la la-remove"></i>
                            <span>Delete</span>
                        </span>
                    </a>';
        })->rawColumns(['checkbox', 'link', 'action'])->make(true);
    }

    public function portfolio()
    {
        return $this->hasMany(Portfolio::class, 'created_by');
    }

    public function designCount(): HasOne
    {
        return $this->hasOne(UserDesignCount::class, "user_id")->withDefault();
    }

    public function incrementLogoCount($field)
    {
        $userDesignCount = UserDesignCount::where("user_id", $this->id)
            ->first();
        if (! $userDesignCount) {
            $userDesignCount = new UserDesignCount();
            $userDesignCount->user_id = $this->id;
            $userDesignCount->$field = 1;
        } else {
            $userDesignCount->$field++;
        }
        $userDesignCount->save();

        return $this;
    }

    public function addDesignCount($free_limit, $premium_limit)
    {
        $userDesignCount = UserDesignCount::where("user_id", $this->id)
            ->first();
        if (! $userDesignCount) {
            $userDesignCount = new UserDesignCount();
            $userDesignCount->user_id = $this->id;
            $userDesignCount->free_limit = $free_limit != -1 ? $free_limit : 0;
            $userDesignCount->premium_limit = $premium_limit != -1 ? $premium_limit : 0;
            $userDesignCount->free_unlimit = $free_limit == -1 ? 1 : 0;
            $userDesignCount->premium_unlimit = $premium_limit == -1 ? 1 : 0;
        } else {
            $userDesignCount->free_limit += $free_limit != -1 ? $free_limit : 0;
            $userDesignCount->premium_limit += $premium_limit != -1 ? $premium_limit : 0;
            $userDesignCount->free_unlimit += $free_limit == -1 ? 1 : 0;
            $userDesignCount->premium_unlimit += $premium_limit == -1 ? 1 : 0;
        }
        $userDesignCount->save();

        return $this;
    }

    public function isDownloadPossible($userLogo)
    {
        return true;
        $result = false;
        if ($this->downloadLogos()->where("logo_id", $userLogo->logo_id)->first()) {
            $result = true;
        } else {
            $logoCount = $this->logoCount;
            $freeLimit = $logoCount->free_limit ?? 0;
            $premiumLimit = $logoCount->premium_limit ?? 0;
            $freeUnlimit = $logoCount->free_unlimit ?? 0;
            $premiumUnlimit = $logoCount->premium_unlimit ?? 0;
            $freeCurrent = $logoCount->free_current ?? 0;
            $premiumCurrent = $logoCount->premium_current ?? 0;

            $freeOfferCount = user()->free_count;

            if (! $userLogo->logo->premium) {
                if ($freeUnlimit > 0) {
                    $this->incrementLogoCount("free_current");
                    $result = true;
                    $this->downloadLogos()->create(['logo_id' => $userLogo->logo_id]);
                } elseif (($freeLimit - $freeCurrent + $freeOfferCount) > 0) {
                    $this->incrementLogoCount("free_current");
                    $result = true;
                    $this->downloadLogos()->create(['logo_id' => $userLogo->logo_id]);
                }
            } else {
                if ($premiumUnlimit > 0) {
                    $this->incrementLogoCount("premium_current");
                    $result = true;
                    $this->downloadLogos()->create(['logo_id' => $userLogo->logo_id]);
                } elseif (($premiumLimit - $premiumCurrent) > 0) {
                    $this->incrementLogoCount("premium_current");
                    $result = true;
                    $this->downloadDesigns()->create(['logo_id' => $userLogo->logo_id]);
                }
            }
        }

        return $result;
    }

    public function downloadDesigns()
    {
        return $this->hasMany(UserDesignDownload::class, "user_id");
    }

    /**
     * Check if user's website has any blog module
     * @return bool
     */
    public function hasWebsiteBlogModule($websiteId = null)
    {
        // do not show blog page if user's website do not have any blog module
        $userWebsites = user()->websites->pluck('id');

        return Website::with('activeModules')->select('id', 'user_id')
            ->whereIn('id', $userWebsites)
            ->when($websiteId, function ($q) use ($websiteId) {
                return $q->where('id', $websiteId);
            })
            ->whereHas('activeModules', function ($q) {
                $q->where('slug', 'like', '%_blog%');
            })->exists();
    }

    /**
     * Check if user's website has any appointment module
     * @return bool
     */
    public function hasWebsiteAppointmentModule($websiteId = null)
    {
        $hasAppointments = user()->appointments->count();
        if ($hasAppointments) {
            return true;
        }

        $hasMeetings = UserMeeting::with('model')->where("status", "active")->count();
        if ($hasMeetings) {
            return true;
        }

        // do not show appointment page if user's website do not have any blog module
        $userWebsites = user()->websites->pluck('id');

        return Website::with('activeModules')->select('id', 'user_id')
            ->whereIn('id', $userWebsites)
            ->when($websiteId, function ($q) use ($websiteId) {
                return $q->where('id', $websiteId);
            })
            ->whereHas('activeModules', function ($q) {
                $q->where('slug', 'like', '%appointment%');
            })->exists();
    }

    /**
     * Check if user's website has any eCommerce module
     * @return bool
     */
    public function hasWebsiteEcommerceModule($websiteId = null)
    {
        // do not show ecommerce page if user's website do not have any blog module
        $userWebsites = user()->websites->pluck('id');

        return Website::with('activeModules')->select('id', 'user_id')
            ->whereIn('id', $userWebsites)
            ->when($websiteId, function ($q) use ($websiteId) {
                return $q->where('id', $websiteId);
            })
            ->whereHas('activeModules', function ($q) {
                $q->where('slug', 'like', '%ecommerce%');
            })->exists();
    }

    // Define a one-to-one relationship with Social
    public function socialAccount()
    {
        return $this->hasOne(SocialAccount::class, 'username', 'username');
    }

    // Define a one-to-one relationship with Community
    public function communityAccount()
    {
        return $this->hasOne(CommunityAccount::class, 'username', 'username');
    }
}
