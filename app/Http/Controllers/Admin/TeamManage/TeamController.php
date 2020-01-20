<?php

namespace App\Http\Controllers\Admin\TeamManage;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Team;
use App\Models\TeamProperty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class TeamController extends AdminController
{
    public function __construct()
    {
        $this->model = new Team();
    }
    public function index()
    {
        if (request()->wantsJson()) {
            $teams = $this->model->with('media', 'properties', 'users.media')
                ->withCount('subTeams')
                ->get();
            $all_teams = $teams->where('parent_id', 0);
            $active_teams = $all_teams->where('status', 1);
            $inactive_teams = $all_teams->where('status', 0);

            $all = view("components.admin.teamTable", [
                'teams' => $all_teams,
                'selector' => 'datatable-all',
                'subteam' => 0,
            ])->render();

            $active = view("components.admin.teamTable", [
                'teams' => $active_teams,
                'selector' => 'datatable-all',
                'subteam' => 0,
            ])->render();

            $inactive = view("components.admin.teamTable", [
                'teams' => $inactive_teams,
                'selector' => 'datatable-all',
                'subteam' => 0,
            ])->render();

            $count['all'] = $all_teams->count();
            $count['active'] = $active_teams->count();
            $count['inactive'] = $inactive_teams->count();

            return response()->json([
               'status' => 1,
               'all' => $all,
               'active' => $active,
               'inactive' => $inactive,
               'count' => $count,
            ]);
        }

        return view(self::$viewDir . "teamManage.team");
    }
    public function create($slug = null)
    {
        if ($slug == null) {
            $properties = TeamProperty::whereStatus(1)
                ->orderBy('order')
                ->get();
        } else {
            $properties = $this->model->whereSlug($slug)
                ->whereParentId(0)
                ->firstorfail()
                ->properties;
        }

        return view(self::$viewDir . "teamManage.createTeam", compact("properties", "slug"));
    }
    public function selectUser(Request $request)
    {
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;

            $q = User::query();
            if ($request->slug != null) {
                $team = $this->model->whereSlug($request->slug)
                    ->whereParentId(0)
                    ->firstorfail();
            } else {
                $team = null;
            }

            if ($request->user == 'user') {
                if ($team == null) {
                    $q = $q->doesntHave('roles');
                } else {
                    $q = $team->users()->wherePivot('role', 'user');
                }
            } elseif ($request->user == 'employee') {
                if ($team == null) {
                    $q = $q->role('employee');
                } else {
                    $q = $team->users()->wherePivot('role', 'employee');
                }
            } elseif ($request->user == 'client') {
                if ($team == null) {
                    $q = $q->role('client');
                } else {
                    $q = $team->users()->wherePivot('role', 'client');
                }
            }

            $data = $q->where(function ($query) use ($search) {
                $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%$search%");
                $query->orWhere("users.email", "LIKE", "%$search%");
            })
            ->selectRaw('CONCAT( first_name, " ", last_name,  " (", email, ")" ) as nameEmail, users.id')
            ->status('active')
            ->paginate(50);
        }

        return response()->json($data);
    }
    public function store(Request $request, $slug = null)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule($slug), $this->model::CUSTOM_VALIDATION_MESSAGE);
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
            $team = $this->model->storeItem($request, $slug);

            return response()->json([
                'status' => 1,
                'data' => $team,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function edit($id)
    {
        $team = $this->model->with('properties', 'media', 'users')->findorfail($id);
        if ($team->parent_id == 0) {
            $properties = TeamProperty::whereStatus(1)
                ->orderBy('order')
                ->get();
            $slug = null;
        } else {
            $slug = $team->parent->slug;
            $properties = $this->model->whereSlug($slug)
                ->whereParentId(0)
                ->firstorfail()
                ->properties;
        }

        return view(self::$viewDir . "teamManage.editTeam", compact('team', 'properties', 'slug'));
    }
    public function update(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule(), $this->model::CUSTOM_VALIDATION_MESSAGE);
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
            $team = $this->model->findorfail($id)->storeItem($request, $request->slug);

            return response()->json([
                'status' => 1,
                'data' => $team,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function subteam($slug)
    {
        $team = $this->model->whereParentId(0)
            ->whereSlug($slug)
            ->firstorfail();
        if (request()->wantsJson()) {
            $all_teams = $team->subteams;
            $active_teams = $all_teams->where('status', 1);
            $inactive_teams = $all_teams->where('status', 0);

            $all = view("components.admin.teamTable", [
                'teams' => $all_teams,
                'selector' => 'datatable-all',
                'subteam' => 1,
            ])->render();

            $active = view("components.admin.teamTable", [
                'teams' => $active_teams,
                'selector' => 'datatable-all',
                'subteam' => 1,
            ])->render();

            $inactive = view("components.admin.teamTable", [
                'teams' => $inactive_teams,
                'selector' => 'datatable-all',
                'subteam' => 1,
            ])->render();

            $count['all'] = $all_teams->count();
            $count['active'] = $active_teams->count();
            $count['inactive'] = $inactive_teams->count();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'active' => $active,
                'inactive' => $inactive,
                'count' => $count,
            ]);
        }

        return view(self::$viewDir . "teamManage.subTeam", compact('team'));
    }
}
