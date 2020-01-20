<?php

namespace App\Http\Controllers\Client;

use App\Models\AppointmentList;
use App\Models\Ticket;
use App\Models\UserForm;
use Carbon\Carbon;

class DashboardController extends ClientController
{
    public function index()
    {
        $now = Carbon::now()->toDateString();

        $data['pendingForms'] = UserForm::where("status", "need to fill")
            ->my()
            ->get();
        $data['comingAppointments'] = AppointmentList::with("user")
            ->where("date", ">=", $now)
            ->where("status", "approved")
            ->my()
            ->get();
        $data['notifications'] = auth()->user()->unreadNotifications;
        $data['openedTickets'] = Ticket::with("user")
            ->where("parent_id", 0)
            ->where("status", "!=", "closed")
            ->my()
            ->get();

        return view(self::$viewDir.'.dashboard', $data);
    }
}
