<?php

namespace App\Models;

use Yajra\DataTables\Facades\DataTables;

class WebsiteUserAppointmentList extends BaseModel
{
    protected $connection = 'mysql2';
    protected $table = "appointment_lists";

    protected $guarded = ["id", "created_at", "updated_at"];

    public function storeRule()
    {
        $rule['website_id'] = 'required';
        $rule['category_id'] = 'required|exists:mysql2.appointment_categories,id';
        $rule['date'] = 'required';
        $rule['start'] = 'required';
        $rule['end'] = 'required';

        return $rule;
    }
    public function updateRule()
    {
        $rule['website_id'] = 'required';
        $rule['category'] = 'required|exists:mysql2.appointment_categories,id';
        $rule['date'] = 'required';
        $rule['start_time'] = 'required';
        $rule['end_time'] = 'required|after:start_time';
        $rule['reason'] = 'nullable|max:600';
        $rule['description'] = 'nullable|max:600';
        $rule['status'] = 'required|in:approved,pending,canceled,done';

        return $rule;
    }
    public function storeItem($request)
    {
        $item = $this;
        $item->website_id = $request->website_id;
        $item->category_id = $request->category_id;
        $item->user_id = $request->user_id;
        $item->date = $request->date;
        $item->start_time = $request->start;
        $item->end_time = $request->end;
        $item->status = $request->status;
        $item->reason = $request->reason;
        $item->description = $request->description;
        $item->save();

        return $item;
    }
    public function updateItem($request)
    {
        $item = $this;
        $item->website_id = $request->website_id;
        $item->category_id = $request->category;
        $item->date = $request->date;
        $item->start_time = $request->start_time;
        $item->end_time = $request->end_time;
        $item->status = $request->status;
        $item->reason = $request->reason;
        $item->description = $request->description;
        $item->save();

        return $item;
    }
    public function getEvents($request, $user_id = 0)
    {
        $start = $request->start;
        $end = $request->end;

        if ($user_id == 0) {
            $lists = $this->whereBetween("date", [$start, $end])->with('user')
                ->get();
        } else {
            $lists = $this->where("user_id", user()->id)
                ->whereBetween("date", [$start, $end])
                ->get();
        }
        $events = [];
        if ($lists != null) {
            foreach ($lists as $list) {
                $event = [];
                $event['start'] = $list->date . " " . timeGlobal($list->start_time);
                $event['end'] = $list->date . " " . timeGlobal($list->end_time);
                $event['reason'] = $list->reason;
                $event['status'] = ucfirst($list->status);
                if ($list->status == "approved") {
                    $color = "#34bfa3";
                } elseif ($list->status == "pending") {
                    $color = "#36a3f7";
                } elseif ($list->status == "canceled") {
                    $color = "#CF1124";
                } else {
                    $color = "#32a506";
                }
                if ($user_id == 0) {
                    $event['site'] = $list->site->domain;
                    $event['title'] = $list->start_time . " - " . $list->end_time . " (" . $list->site->domain . ")";
                } else {
                    $event['site'] = $list->site->domain;
                    $event['title'] = $list->start_time . " - " . $list->end_time;
                }
                $event['user'] = $list->user->name;
                $event['color'] = $color;
                $event['id'] = $list->id;
                $event['date'] = $list->date;
                $event['product'] = $list->category->name ?? '';
                $event['start_time'] = $list->start_time;
                $event['end_time'] = $list->end_time;
                $event['allDay'] = false;
                array_push($events, $event);
            }
        }

        return $events;
    }

    public function getDatatable($status, $user)
    {
        switch ($status) {
            case 'all':
                $appointments = $this::with('site', 'meeting');

                break;
            case 'approved':
                $appointments = $this::with('user', 'meeting')->whereStatus("approved");

                break;
            case 'pending':
                $appointments = $this::with('user', 'meeting')->whereStatus("pending");

                break;
            case 'canceled':
                $appointments = $this::with('user', 'meeting')->whereStatus("canceled");

                break;
            case 'done':
                $appointments = $this::with('user', 'meeting')->whereStatus("done");

                break;
        }
        if ($user != 'all') {
            $appointments = $appointments->my($user);
        }

        return Datatables::of($appointments)
        ->addColumn('checkbox', function ($row) {
            return '<input type="checkbox" class="checkbox" data-id="'.$row->id.'">';
        })
        ->addColumn('site', function ($row) {
            // return "<img src='".$row->user->avatar()."' title='".$row->user->name."' class='user-avatar-50'><br>{$row->user->name}<br>({$row->user->email})";
            return "<a href='//".$row->site->domain."' target='_blank'>".$row->site->domain."<i class='la la-external-link'></i></a>";
        })
        ->addColumn('user', function ($row) {
            return "<img src='".$row->user->avatar()."' title='".$row->user->name."' class='user-avatar-50 mx-auto'>{$row->user->name}<br>({$row->user->email})";
        })
        ->addColumn('time', function ($row) {
            return "{$row->start_time} - {$row->end_time}";
        })
        ->addColumn('product', function ($row) {
            if (isset($row->meeting->model)) {
                return $row->meeting->model->getName() ?? '';
            } else {
                return '';
            }
        })
        ->editColumn('status', function ($row) {
            if ($row->status === 'approved') {
                $result = "<span class='c-badge c-badge-success white-space-nowrap'>".$row->status."</span>";
            } elseif ($row->status === 'pending') {
                $result = "<span class='c-badge c-badge-danger white-space-nowrap'>".$row->status."</span>";
            } else {
                $result = "<span class='c-badge c-badge-info white-space-nowrap'>".$row->status."</span>";
            }

            return $result;
        })
        ->addColumn('action', function ($row) {
            return '<a href="'.route('user.appointment.listing.detail', $row->id).'" class="btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon">
                        <span>
                            <span>Detail</span>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm m-1	p-2 m-btn m-btn--icon switchOne"  data-action="delete">
                        <span>
                            <span>Delete</span>
                        </span>
                    </a>
                    ';
        })
        ->rawColumns(['checkbox', 'site','user' , 'status', 'action'])->make(true);
    }
    public function category()
    {
        return $this->belongsTo(UserAppointmentCategory::class, "category_id")->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(WebsiteUser::class, "user_id")->withDefault();
    }
    public function meeting()
    {
        return $this->belongsTo(UserMeeting::class, "meeting_pid")->withDefault();
    }
    public function site()
    {
        return $this->belongsTo(Website::class, "web_id")->withDefault();
    }
}
