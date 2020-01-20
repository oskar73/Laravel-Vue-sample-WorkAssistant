<?php

namespace App\Http\Controllers\Admin;

use App\Integration\GoogleAnalytics;
use App\Models\AppointmentList;
use App\Models\BlogAdsListing;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\NewsletterAdsListing;
use App\Models\Subscriber;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserForm;
use App\Models\Website;
use App\Models\WebsiteSubscriber;
use App\Models\WebsiteUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends AdminController
{
    public $period = 29;

    public function test($name)
    {
        if ($name == 'ssh') {
        } elseif ($name == 'queue') {
        } elseif ($name == 'email') {
            try {
            } catch (\Exception $e) {
                return json_encode($e->getMessage());
            }
        }
    }

    public function index()
    {
        $now = Carbon::now()->toDateString();
        $data['pendingPosts'] = BlogPost::where("is_published", 1)->where("status", "pending")->count();
        $data['pendingComments'] = BlogComment::where("status", 0)->count();
        $data['pendingListings'] = BlogAdsListing::where("status", "pending")->count();
        $data['pendingNewsletterAdListings'] = NewsletterAdsListing::where('status', 'pending')->count();
        $data['pendingForms'] = UserForm::where("status", "filled")->whereNull("read_at")->count();
        $data['pendingAppointments'] = AppointmentList::where("date", ">=", $now)->where("status", "pending")->count();
        $data['comingAppointments'] = AppointmentList::with("user")->where("date", ">=", $now)->where("status", "approved")->get();
        $data['openedTickets'] = Ticket::with("user")->where("parent_id", 0)->where("status", "!=", "closed")->latest()->get();
        $data['notifications'] = auth()->user()->unreadNotifications;

        $data['websites'] = Website::get(["id", "name", "domain"]);

        $data['credential_json_exists'] = GoogleAnalytics::credentialJsonFileExists();

        return view('admin.dashboard', $data);
    }

    public function getCardData(Request $request)
    {
        $id = $request->id;
        if ($id == 0) {
            $data['totalUsers'] = User::count();
            $data['verifiedUsers'] = User::whereNotNull("email_verified_at")->count();
            $data['todayUsers'] = User::where("created_at", ">", now()->toDateString())->count();
            $data['totalSubscribers'] = Subscriber::where("status", 1)->count();
        } else {
            $data['totalUsers'] = WebsiteUser::where("web_id", $id)->count();
            $data['verifiedUsers'] = WebsiteUser::where("web_id", $id)->whereNotNull("email_verified_at")->count();
            $data['todayUsers'] = WebsiteUser::where("web_id", $id)->where("created_at", ">", now()->toDateString())->count();
            $data['totalSubscribers'] = WebsiteSubscriber::where("web_id", $id)->where("status", 1)->count();
        }

        $view = view("components.admin.dashboardCard", $data)->render();

        return $this->jsonSuccess($view);
    }

    public function analytics()
    {
        try {
            config([
                "analytics.property_id" => option("analytics_property_id"),
            ]);

            GoogleAnalytics::loadCredentialJsonFile();

            $data = GoogleAnalytics::totalVisitorsAndPageViews($this->period);
            $data['browserjson'] = GoogleAnalytics::topBrowsers($this->period);

            $result = GoogleAnalytics::country($this->period);
            $data['country'] = $result->pluck('country');
            $data['country_sessions'] = $result->pluck('sessions');

            return response()->json([
                'status' => 1,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            logger($e);

            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }

    public function submitAnalytics(Request $request)
    {
        $validation = \Validator::make($request->all(), [
            'json_file' => 'mimes:json|max:2048',
            'property_id' => 'nullable|integer',
        ]);
        if ($validation->fails()) {
            return response()->json(['status' => 0, 'data' => $validation->errors()]);
        }
        if ($request->json_file) {
            GoogleAnalytics::uploadCredentialJsonFile($request->json_file);
        }

        option(['analytics_property_id' => $request->property_id]);

        return response()->json(['status' => 1, 'data' => 1]);
    }

    public function revokeAnalytics()
    {
        GoogleAnalytics::deleteCredentialJsonFile();

        return response()->json(['status' => 1, 'data' => 1]);
    }

    public function selectUser(Request $request)
    {
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;

            $data = User::query()->where(function ($query) use ($search) {
                $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%$search%");
                $query->orWhere("users.email", "LIKE", "%$search%");
            })
                ->selectRaw('CONCAT( first_name, " ", last_name,  " (", email, ")" ) as nameEmail, users.id')
                ->status('active')
                ->paginate(50);
        }

        return response()->json($data);
    }

    public function getTitle(Request $request)
    {
        $title = option($request->title_edit_page, []);

        return $title[$request->title_edit_id];
    }

    public function saveTitle(Request $request)
    {
        $this->validate($request, [
            'title_edit_page' => 'required|string|max:191',
            'title_edit_id' => 'required|string|max:191',
            'title_edit' => 'required|max:255',
        ]);

        $title = option($request->title_edit_page, []);

        $title[$request->title_edit_id] = $request->title_edit;

        option([$request->title_edit_page => $title]);

        return back()->with('success', "Success!");
    }
}
