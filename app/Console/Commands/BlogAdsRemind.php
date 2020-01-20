<?php

namespace App\Console\Commands;

use App\Models\BlogAdsListing;
use App\Models\Error;
use App\Models\NotificationTemplate;
use Illuminate\Console\Command;

class BlogAdsRemind extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blogads:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Blog Ads Listing Remind';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->listingExpireRemind();

            $this->paidListingRemind();
        } catch (\Exception $e) {
            Error::create([
                'location' => 'App\Console\Commands\BlogAdsRemind::handle()',
                'error' => json_encode($e->getMessage()),
            ]);
        }
    }
    public function paidListingRemind()
    {
        $listings = BlogAdsListing::with("user", "events")
            ->where("status", "paid")
            ->get();
        foreach ($listings as $listing) {
            $event = $listing->events->sortBy("start_date")->first();

            if ($event->start_date <= now()->toDateString()) {
                $this->paidRemindNotification($listing, $event->start_date);
            } elseif (
                in_array($event->start_date, [
                    now()->addDays(1)->toDateString(),
                    now()->addDays(2)->toDateString(),
                    now()->addDays(3)->toDateString(),
                ])) {
                $this->paidRemindNotification($listing, $event->start_date);
            }
        }
    }
    public function paidRemindNotification($listing, $date)
    {
        $notification = new NotificationTemplate();
        $data['url'] = route('user.blogAds.detail', $listing->id);
        $data['username'] = $listing->user->name;
        $data['date'] = $date;

        $notification->sendNotification($data, $notification::BLOG_ADS_PAID_REMIND, $listing->user);
    }
    public function listingExpireRemind()
    {
        $listings = BlogAdsListing::with("user", "events")
            ->whereNull("impression_number")
            ->where("status", "!=", "expired")
            ->get();

        foreach ($listings as $listing) {
            $event = $listing->events->sortByDesc("end_date")->first();


            if ($event->end_date < now()->toDateString()) {
                $listing->status = "expired";
                $listing->save();
            } elseif (in_array($event->end_date, [
                now()->toDateString(),
                now()->addDays(1)->toDateString(),
                now()->addDays(2)->toDateString(),
                now()->addDays(3)->toDateString(),
            ])) {
                $this->expireRemindNotification($listing, $event->end_date);
            }
        }
    }

    public function expireRemindNotification($listing, $date)
    {
        $notification = new NotificationTemplate();
        $data['url'] = route('user.blogAds.detail', $listing->id);
        $data['username'] = $listing->user->name;
        $data['date'] = $date;

        $notification->sendNotification($data, $notification::BLOG_ADS_EXPIRE_REMIND, $listing->user);
    }
}
