<?php

namespace App\Http\Controllers\Admin\Purchase;

use App\Http\Controllers\Admin\AdminController;
use App\Models\UserBlogPackage;
use App\Models\UserLacarte;
use App\Models\UserPackage;
use App\Models\UserPlugin;
use App\Models\UserService;

class ProductController extends AdminController
{
    public function package()
    {
        if (request()->wantsJson()) {
            $model = new UserPackage();

            return $model->getDatatable(request()->get("status"), request()->get("user"), "package");
        }

        return view(self::$viewDir.'purchase.package', ["item" => "package"]);
    }
    public function packageDetail($id)
    {
        $item = UserPackage::where("package", 1)->where("id", $id)->firstorfail();
        $detail = json_decode($item->item);
        $type = "package";

        return view(self::$viewDir.'purchase.packageDetail', compact("item", "detail", "type"));
    }
    public function readymade()
    {
        if (request()->wantsJson()) {
            $model = new UserPackage();

            return $model->getDatatable(request()->get("status"), request()->get("user"), "readymade");
        }

        return view(self::$viewDir.'purchase.package', ["item" => "readymade"]);
    }
    public function readymadeDetail($id)
    {
        $item = UserPackage::where("package", 0)->where("id", $id)->firstorfail();
        $detail = json_decode($item->item);
        $type = "readymade";

        return view(self::$viewDir.'purchase.packageDetail', compact("item", "detail", "type"));
    }
    public function lacarte()
    {
        if (request()->wantsJson()) {
            $model = new UserLacarte();

            return $model->getDatatable(request()->get("status"),  request()->get("user"));
        }

        return view(self::$viewDir.'purchase.lacarte');
    }
    public function lacarteDetail($id)
    {
        $item = UserLacarte::findorfail($id);
        $detail = json_decode($item->item);

        return view(self::$viewDir.'purchase.lacarteDetail', compact("item", "detail"));
    }
    public function plugin()
    {
        if (request()->wantsJson()) {
            $model = new UserPlugin();

            return $model->getDatatable(request()->get("status"),  request()->get("user"));
        }

        return view(self::$viewDir.'purchase.plugin');
    }
    public function pluginDetail($id)
    {
        $item = UserPlugin::findorfail($id);
        $detail = json_decode($item->item);

        return view(self::$viewDir.'purchase.pluginDetail', compact("item", "detail"));
    }
    public function service()
    {
        if (request()->wantsJson()) {
            $model = new UserService();

            return $model->getDatatable(request()->get("status"),  request()->get("user"));
        }

        return view(self::$viewDir.'purchase.service');
    }
    public function serviceDetail($id)
    {
        $item = UserService::findorfail($id);
        $detail = json_decode($item->item);

        return view(self::$viewDir.'purchase.serviceDetail', compact("item", "detail"));
    }
    public function blog()
    {
        if (request()->wantsJson()) {
            $model = new UserBlogPackage();

            return $model->getDatatable(request()->get("status"));
        }

        return view(self::$viewDir.'purchase.blog');
    }
    public function blogDetail($id)
    {
        $item = UserBlogPackage::findorfail($id);
        $detail = json_decode($item->item);

        return view(self::$viewDir.'purchase.blogDetail', compact("item", "detail"));
    }
}
