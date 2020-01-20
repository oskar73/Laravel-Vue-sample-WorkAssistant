<?php

namespace App\Console\Commands;

use App\Models\AppointmentList;
use App\Models\Error;
use App\Models\NotificationTemplate;
use Illuminate\Console\Command;

class AppointmentRemind extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointment:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scheduled Appointment Remind';

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
            $time = now()->toTimeString();
            $time_later = now()->addMinutes(60)
                ->toTimeString();

            $appointments = AppointmentList::where("date", $today)
                ->with('user')
                ->where("start_time", ">=", $time)
                ->where("start_time", "<=", $time_later)
                ->whereIn("status", ["approved", "pending"])
                ->get();


            if ($appointments->count() == 0) {
                return true;
            }

            $this->handleAppointments($appointments);
        } catch (\Exception $e) {
            Error::create([
                'location' => 'App\Console\Commands\AppointmentRemind::handle()',
                'error' => json_encode($e->getMessage()),
            ]);
        }
    }
    public function handleAppointments($appointments)
    {
        foreach ($appointments as $appointment) {
            if ($appointment->start_time <= now()->toTimeString()) {
                $appointment->status = 'done';
                $appointment->save();
            } elseif ($appointment->start_time <= now()->addMinutes(5)->toTimeString() && $appointment->start_time >= now()->toTimeString()) {
                $this->notify($appointment);
            } elseif ($appointment->start_time <= now()->addMinutes(10)->toTimeString() && $appointment->start_time >= now()->addMinutes(15)->toTimeString()) {
                $this->notify($appointment);
            } elseif ($appointment->start_time <= now()->addMinutes(30)->toTimeString() && $appointment->start_time >= now()->addMinutes(25)->toTimeString()) {
                $this->notify($appointment);
            } elseif ($appointment->start_time <= now()->addMinutes(60)->toTimeString() && $appointment->start_time >= now()->addMinutes(55)->toTimeString()) {
                {
                    $this->notify($appointment);
                }
            }
        }
    }
    public function notify($appointment)
    {
        $notification = new NotificationTemplate();


        if ($appointment->status == 'pending') {
            $data['user'] = $appointment->user->name;
            $data['time'] = $appointment->start_time;
            $data['slug'] = $notification::APPOINTMENT_APPROVAL_REMIND;
            $data['url'] = route('admin.appointment.listing.detail', $appointment->id);

            $notification->sendNotificationToAdmin($data);
        } else {
            $data['detail'] = "User: {$appointment->user->name} <br> Time:{$appointment->start_time} - {$appointment->end_time} <br> Description: {$appointment->description}";
            $data['url'] = route('admin.appointment.listing.detail', $appointment->id);
            $data['slug'] = $notification::APPOINTMENT_REMIND;

            $notification->sendNotificationToAdmin($data);

            $data1['username'] = $appointment->user->name;
            $data1['detail'] = "Time:{$appointment->start_time} - {$appointment->end_time} <br> Description: {$appointment->description}";
            $data1['url'] = route('user.appointment.detail', $appointment->id);

            $notification->sendNotification($data1, $notification::APPOINTMENT_REMIND, $appointment->user);
        }
    }
}
