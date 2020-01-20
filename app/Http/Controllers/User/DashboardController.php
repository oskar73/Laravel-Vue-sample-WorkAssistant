<?php

namespace App\Http\Controllers\User;

use App\Models\Announcement;
use App\Models\AppointmentList;
use App\Models\Domain;
use App\Models\NewsletterAdsListing;
use App\Models\Ticket;
use App\Models\Tutorial;
use App\Models\UserForm;
use App\Models\UserPackage;
use App\Models\WidgetCategory;
use Carbon\Carbon;

class DashboardController extends UserController
{
    public function index()
    {
        $announcements = Announcement::where(function ($q) {
            $q->where("user_id", 0);
            $q->orWhere("user_id", user()->id);
        })->where("user_id", 0)
            ->where('status', 1)
            ->latest()
            ->get(['title', 'id', 'content', 'created_at']);

        $data['announcements'] = $announcements;

        $widgets = WidgetCategory::where('status', 1)->with('items')->orderBy('order')->get();
        $data['widgets'] = $widgets;

        $userPackages = UserPackage::my()
            ->with("websites", "progresses")
            ->status("active")
            ->get();
        $data['packages'] = $userPackages;

        $data['newsletterAds'] = NewsletterAdsListing::my()->with('position')->get();

        return view(self::$viewDir . 'dashboard', $data);
    }

    public function started()
    {
        $modules = [];
        $tutorials = Tutorial::whereNotIn("module_id", $modules)
            ->where('status', 1)
            ->with("media", "modules")
            ->orderBy("order")
            ->get();

        $package = UserPackage::where("status", "active")
            ->my()
            ->count();
        $domain = Domain::where("status", "active")
            ->whereNull("web_id")
            ->my()
            ->count();

        return view(self::$viewDir . 'started', compact("tutorials", "package", "domain"));
    }
}
