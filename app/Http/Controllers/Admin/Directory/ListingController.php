<?php

namespace App\Http\Controllers\Admin\Directory;

use App\Http\Controllers\Admin\AdminController;
use App\Models\DirectoryCategory;
use App\Models\DirectoryListing;
use App\Models\DirectoryTag;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;
use Validator;

class ListingController extends AdminController
{
    public function __construct(DirectoryListing $model)
    {
        $this->model = $model;
    }
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->model->getDatatable(request()->get("status"), request()->get("user"));
        }

        return view(self::$viewDir . "directory.listing");
    }
    public function create()
    {
        $categories = DirectoryCategory::where('parent_id',  0)
            ->with('approvedSubCategories.approvedTags', 'approvedTags')
            ->status(1)
            ->get(['id', 'name']);

        $tags = DirectoryTag::whereStatus(1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view(self::$viewDir . "directory.listingCreate", compact("categories", "tags"));
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                $this->model->storeRule($request),
                $this->model::CUSTOM_VALIDATION_MESSAGE
            );

            if ($validation->fails()) {
                return response()->json(['status' => 0, 'data' => $validation->errors()]);
            }
            if ($request->listing_id) {
                $item = $this->model->findorfail($request->listing_id)
                    ->updateItem($request);
            } else {
                $item = $this->model->storeItem($request);
                $item->approved_at = null;
                $item->save();
            }

            return $this->jsonSuccess($item);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
    public function edit($slug)
    {
        $listing = $this->model->where("slug", $slug)->firstorfail();
        $categories = DirectoryCategory::where('parent_id',  0)
            ->with('approvedSubCategories.approvedTags', 'approvedTags')
            ->status(1)
            ->get(['id', 'name']);

        $tags = DirectoryTag::whereStatus(1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view(self::$viewDir . "directory.listingEdit", compact("categories", "listing", "tags"));
    }

    public function preview($slug)
    {
        $listing = $this->model->where("slug", $slug)->firstorfail();
        $categories = DirectoryCategory::where('parent_id',  0)
            ->with('approvedSubCategories.approvedTags', 'approvedTags')
            ->status(1)
            ->get(['id', 'name']);

        $tags = DirectoryTag::whereStatus(1)
            ->orderBy('name')
            ->get(['id', 'name']);

        $seo = option("directory.front.seo", null);

        return view(self::$viewDir . "directory.listingPreview", compact("categories", "listing", "tags", "seo"));
    }

    public function show($slug)
    {
        $listing = $this->model->where("slug", $slug)->firstorfail();
        $categories = DirectoryCategory::where('parent_id',  0)
            ->with('approvedSubCategories.approvedTags', 'approvedTags')
            ->status(1)
            ->get(['id', 'name']);

        $tags = DirectoryTag::whereStatus(1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view(self::$viewDir . "directory.listingShow", compact("categories", "listing", "tags"));
    }

    public function approve($id)
    {
        try {
            $item = $this->model->find($id);
            $item->approved_at = date('Y-m-d h:i:s');
            $item->status = 'approved';
            $item->save();
            $notification = new NotificationTemplate();
            $data = ['url' => route('directory.index')];
            $notification->sendEmail($data, NotificationTemplate::DIRECTORY_APPROVAL, $item->creator->email);

            return back()->with('success', 'Directory Listing Approved!');
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function deny($id)
    {
        try {
            $item = $this->model->find($id);
            $item->approved_at = null;
            $item->status = 'denied';
            $item->save();
            $notification = new NotificationTemplate();
            $data = ['url' => route('directory.index')];
            $notification->sendEmail($data, NotificationTemplate::DIRECTORY_APPROVAL, $item->creator->email);

            return back()->with('success', 'Directory Listing Denied!');
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
}
