<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Models\Error;
use App\Models\NotificationTemplate;
use Illuminate\Console\Command;

class DomainCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Domain Check';

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
            $today = now()->toDateString();
            $later = now()->addDays(15)->toDateString();

            $domains = Domain::with('user')
                ->where("status", "active")
                ->where("expired_at", ">=", $today)
                ->where("expired_at", "<=", $later)
                ->get();

            if ($domains->count() == 0) {
                return true;
            }

            $this->handleDomains($domains);
        } catch (\Exception $e) {
            Error::create([
                'location' => 'App\Console\Commands\DomainCheck::handle()',
                'error' => json_encode($e->getMessage()),
            ]);
        }
    }
    public function handleDomains($domains)
    {
        $today = now()->toDateString();

        foreach ($domains as $domain) {
            if ($domain->expired_at == $today) {
                $domain->status = 'expired';
                $domain->save();

                $this->notify($domain, 0);
            } elseif ($domain->expired_at == now()->addDays(1)->toDateString()) {
                $this->notify($domain, 1);
            } elseif ($domain->expired_at == now()->addDays(2)->toDateString()) {
                $this->notify($domain, 2);
            } elseif ($domain->expired_at == now()->addDays(3)->toDateString()) {
                $this->notify($domain, 3);
            } elseif ($domain->expired_at == now()->addDays(7)->toDateString()) {
                $this->notify($domain, 7);
            } elseif ($domain->expired_at == now()->addDays(10)->toDateString()) {
                $this->notify($domain, 10);
            } elseif ($domain->expired_at == now()->addDays(15)->toDateString()) {
                $this->notify($domain, 15);
            }
        }
    }
    public function notify($domain, $day)
    {
        $data['url'] = route('user.domainList.show', $domain->id);
        $data['username'] = $domain->user->name ?? '';
        $data['domain'] = $domain->name ?? '';

        $notification = new NotificationTemplate();
        if ($day == 0) {
            $notification->sendNotification($data, $notification::DOMAIN_EXPIRED, $domain->user);
        } else {
            $data['day'] = $day;
            $notification->sendNotification($data, $notification::DOMAIN_EXPIRED_SOON, $domain->user);
        }
    }
}
