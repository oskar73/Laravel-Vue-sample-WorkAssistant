<?php

namespace App\Console\Commands;

use App\Models\EmailCampaign;
use App\Models\Error;
use App\Models\NotificationTemplate;
use Illuminate\Console\Command;

class CampaignSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaign:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scheduled Email Campaign Send Command';

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
            $time = now()->toDateTimeString();

            $campaigns = EmailCampaign::where("time", "<=", $time)
                ->where("status", 1)
                ->get();

            if ($campaigns == null) {
                return true;
            }
            foreach ($campaigns as $campaign) {
                $campaign->sendCampaign();
                $this->notify($campaign);
            }
        } catch (\Exception $e) {
            Error::create([
                'location' => 'App\Console\Commands\CampaignSend::handle()',
                'error' => json_encode($e->getMessage()),
            ]);
        }
    }
    public function notify($campaign)
    {
        $data['url'] = route('admin.email.campaign.show', $campaign->id);
        $data['title'] = $campaign->subject;
        $data['time'] = $campaign->time;

        $notification = new NotificationTemplate();
        $data['slug'] = $notification::CAMPAIGN_SENT;

        $notification->sendNotificationToAdmin($data);
    }
}
