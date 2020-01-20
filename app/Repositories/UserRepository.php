<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\BlogAdsListing;
use App\Models\NewsletterAdsListing;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserAppointmentList;
use App\Models\WebsiteUserAppointmentList;
use App\Models\UserBlogPackage;
use App\Models\UserForm;
use App\Models\UserMeeting;
use App\Models\UserPackage;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public $model = User::class;

    public function create(array $data): User
    {
        $newsletter = $data['newsletter'];

        unset($data['newsletter']);

        $user = DB::transaction(function () use ($data) {
            if (!data_get($data, 'name')) {
                $fullName = $this->generateNameByEmail(data_get($data, 'email'));
            } else {
                // For social auth registration
                $fullName = data_get($data, 'name');
            }

            return $this->model->create(array_merge([
                'name' => $fullName,
            ], $data));
        });

        if ($newsletter) {
            $user->registerAsSubscriber(false);
        }

        // If register by social auth
        if (data_get($data, 'social_meta')) {
            $user->markEmailAsVerified();
        } else {
            $user->sendPassword();
        }

        return $user;
    }

    public function update(array $data = []): User
    {
        $user = $this->model;

        return DB::transaction(function () use ($user, $data) {
            // Fill data
            $user->fill($data);

            // Delete verification if email is changed
            // and resend email verification
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
                $user->save();
                $user->sendEmailVerificationNotification();

                return $user;
            }

            // Save user
            $user->save();

            return $user;
        });
    }

    protected function generateNameByEmail(string $email): string
    {
        try {
            $name = Arr::first(explode('@', $email));
            $name = Str::replaceArray(' ', ['.', '_', '-'], $name);
            $name = explode(' ', $name);

            $fullName = null;

            foreach ($name as $item) {
                $fullName .= ' ' . ucfirst($item);
            }

            return trim($fullName);
        } catch (\Exception $e) {
            report($e);

            return $email;
        }
    }

    public function getTodos($type)
    {
        $perPage = 20;
        switch ($type) {
            case "blogPost":
                $todos = UserBlogPackage::where("status", "active")->my()->oldest()->paginate($perPage);

                return view("components.user.todo.blogPost", compact("todos"))->render();
            case "blogAdsListing":
                $todos = BlogAdsListing::with('spot')->whereIn("status", ["paid", "denied"])->my()->oldest()->paginate($perPage);

                return view("components.user.todo.blogAdsListing", compact("todos"))->render();
            case "newsletterAdsListing":
                $todos = NewsletterAdsListing::with('position')->whereIn("status", ["paid", "denied"])->my()->oldest()->paginate($perPage);

                return view("components.user.todo.newsletterAdsListing", compact("todos"))->render();
            case "appointment":
                $todos = UserMeeting::with('model')->where("status", "active")->my()->oldest()->paginate($perPage);
                // $todos = WebsiteUserAppointmentList::where('status', 'pending')->get();

                return view("components.user.todo.appointment", compact("todos"))->render();
            case "appointmentReschedule":
                $todos = UserAppointmentList::where("status", "reschedule_requested")->my()->oldest()->paginate($perPage);

                return view("components.user.todo.appointmentReschedule", compact("todos"))->render();
            case "ticket":
                $todos = Ticket::where("parent_id", 0)->my()->where("status", "answered")->oldest()->paginate($perPage);

                return view("components.user.todo.ticket", compact("todos"))->render();
            case "purchaseForm":
                $todos = UserForm::with('model')->whereIn("status", ["need to fill", "need revision"])->my()->oldest()->paginate($perPage);

                return view("components.user.todo.purchaseForm", compact("todos"))->render();
            case "website":
                $todos = UserPackage::status('active')->my()->oldest()->paginate($perPage);

                return view("components.user.todo.website", compact("todos"))->render();
            case "domain":
                $todos = UserPackage::where("status", "active")
                    ->where("domain", "!=", 0)
                    ->where("domain_get", 0)
                    ->my()
                    ->oldest()
                    ->paginate($perPage);

                return view("components.user.todo.domain", compact("todos"))->render();
        }

        return abort(404);
    }

    public function getCounts($type)
    {
        return match ($type) {
            "blogPost" => UserBlogPackage::where("status", "active")->my()->get()->sum('remain_post'),
            "blogAdsListing" => BlogAdsListing::whereIn("status", ["paid", "denied"])->my()->count(),
            "newsletterAdsListing" => NewsletterAdsListing::whereIn("status", ["paid", "denied"])->my()->count(),
            "appointment" => UserMeeting::where("status", "active")->my()->get()->sum('available_number'),
            "appointmentReschedule" => UserAppointmentList::where("status", "reschedule_requested")->my()->get()->count(),
            "ticket" => Ticket::where("parent_id", 0)->my()->where("status", "answered")->count(),
            "purchaseForm" => UserForm::whereIn("status", ["need to fill", "need revision"])->my()->count(),
            "website" => UserPackage::my()->status("active")->get()->sum('remain_website'),
            "domain" => UserPackage::where("status", "active")->my()->where("domain", "!=", 0)->where("domain_get", 0)->count(),
            default => 0,
        };
    }
}
