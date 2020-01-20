<?php

namespace App\Http\Controllers\User\Purchase;

use App\Http\Controllers\User\UserController;
use App\Models\UserBlogPackage;
use App\Models\UserLacarte;
use App\Models\UserPackage;
use App\Models\UserPlugin;
use App\Models\UserService;

class ProductController extends UserController
{
    public function package()
    {
        if (request()->wantsJson()) {
            $items = UserPackage::with("orderItem")
                ->where("package", 1)
                ->where("user_id", user()->id)
                ->latest()
                ->get();
            $count['all'] = $items->count();
            $selector = "datatable-all";
            $type = "package";
            $all = view("components.user.pPackageTable", compact("items", "selector", "type"))->render();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'count' => $count,
            ]);
        }

        return view(self::$viewDir.'purchase.package', ["item" => "package"]);
    }
    public function packageDetail($id)
    {
        $item = UserPackage::where("package", 1)
            ->where("id", $id)
            ->where("user_id", user()->id)
            ->firstorfail();
        $detail = json_decode($item->item);
        $type = "package";

        return view(self::$viewDir.'purchase.packageDetail', compact("item", "detail", "type"));
    }
    public function readymade()
    {
        if (request()->wantsJson()) {
            $items = UserPackage::with("orderItem")
                ->where("package", 0)
                ->where("user_id", user()->id)
                ->latest()
                ->get();
            $count['all'] = $items->count();
            $selector = "datatable-all";
            $type = "readymade";
            $all = view("components.user.pPackageTable", compact("items", "selector", "type"))->render();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'count' => $count,
            ]);
        }

        return view(self::$viewDir.'purchase.package', ["item" => "readymade"]);
    }
    public function readymadeDetail($id)
    {
        $item = UserPackage::where("package", 0)
            ->where("id", $id)
            ->where("user_id", user()->id)
            ->firstorfail();
        $detail = json_decode($item->item);
        $type = "readymade";

        return view(self::$viewDir.'purchase.packageDetail', compact("item", "detail", "type"));
    }
    public function blog()
    {
        if (request()->wantsJson()) {
            $items = UserBlogPackage::with("orderItem")
                ->where("user_id", user()->id)
                ->latest()
                ->get();
            $count['all'] = $items->count();
            $selector = "datatable-all";
            $all = view("components.user.pBlogPackageTable", compact("items", "selector"))->render();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'count' => $count,
            ]);
        }

        return view(self::$viewDir.'purchase.blog');
    }
    public function blogDetail($id)
    {
        $item = UserBlogPackage::whereId($id)
            ->where("user_id", user()->id)
            ->firstorfail();

        $detail = json_decode($item->item);

        return view(self::$viewDir.'purchase.blogDetail', compact("item", "detail"));
    }

    public function lacarte()
    {
        if (request()->wantsJson()) {
            $items = UserLacarte::with("orderItem", "pPackage")
                ->where("user_id", user()->id)
                ->latest()
                ->get();
            $count['all'] = $items->count();
            $selector = "datatable-all";
            $all = view("components.user.pLacarteTable", compact("items", "selector"))->render();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'count' => $count,
            ]);
        }

        return view(self::$viewDir.'purchase.lacarte');
    }
    public function lacarteDetail($id)
    {
        $item = UserLacarte::where("id", $id)
            ->where("user_id", user()->id)
            ->firstorfail();
        $detail = json_decode($item->item);

        return view(self::$viewDir.'purchase.lacarteDetail', compact("item", "detail"));
    }
    public function plugin()
    {
        if (request()->wantsJson()) {
            $items = UserPlugin::with("orderItem", "pPackage")
                ->where("user_id", user()->id)
                ->latest()
                ->get();
            $count['all'] = $items->count();
            $selector = "datatable-all";
            $all = view("components.user.pPluginTable", compact("items", "selector"))->render();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'count' => $count,
            ]);
        }

        return view(self::$viewDir.'purchase.plugin');
    }
    public function pluginDetail($id)
    {
        $item = UserPlugin::where("id", $id)
            ->where("user_id", user()->id)
            ->firstorfail();
        $detail = json_decode($item->item);

        return view(self::$viewDir.'purchase.pluginDetail', compact("item", "detail"));
    }
    public function service()
    {
        if (request()->wantsJson()) {
            $items = UserService::with("orderItem", "pPackage")
                ->where("user_id", user()->id)
                ->latest()
                ->get();
            $count['all'] = $items->count();
            $selector = "datatable-all";
            $all = view("components.user.pServiceTable", compact("items", "selector"))->render();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'count' => $count,
            ]);
        }

        return view(self::$viewDir.'purchase.service');
    }
    public function serviceDetail($id)
    {
        $item = UserService::where("id", $id)
            ->where("user_id", user()->id)
            ->firstorfail();
        $detail = json_decode($item->item);

        return view(self::$viewDir.'purchase.serviceDetail', compact("item", "detail"));
    }
}
