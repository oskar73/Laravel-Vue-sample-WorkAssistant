<?php

namespace App\Http\Controllers\Admin\File;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Session;

class StorageController extends AdminController
{
    public function index()
    {
        return view(self::$viewDir."file.storage");
    }
    public function getData(Request $request)
    {
        try {
            if ($request->get("area") == 'main') {
                Session::put('file.storage', 'main');
            } else {
                Session::put('file.storage', 'users');
            }

            return response()->json(['status' => 1, 'data' => '<iframe src="/account/storage" frameborder="0" class="file_iframe"></iframe>']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function loadSize()
    {
        //        $data = $this->getSize();
        if (Session::get("file.storage") == 'main') {
            $data['count'] = 12000;
            $data['current'] = '500 MB';
            $data['total'] = '15 GB';
            $data['percent'] = '24.5';
        } else {
            $data['count'] = 24500;
            $data['current'] = '120 MB';
            $data['total'] = '5 GB';
            $data['percent'] = '78.5';
        }

        return view('components.account.fileTree', compact("data"))->render();
    }
    public function getSize($id = null)
    {
        $current_size = 0;

        $limit_size = 500;
        $limit_unit = "MB";
        $count = 0;
        $path = public_path("storage/"."11");
        makedir($path);
        foreach (File::allFiles($path) as $file) {
            $current_size += $file->getSize();
            $count++;
        }

        $limit_bytes = $limit_size * ($limit_unit == 'MB'?1:1024) * 1024 * 1024;
        if ($limit_bytes - $current_size > 0) {
            $status = 1;
        } else {
            $status = 0;
        }
        $percent = number_format($current_size / $limit_bytes * 100, 2);
        $c_size = formatSizeUnits($current_size);
        $l_size = $limit_size . $limit_unit;

        $result['percent'] = $percent;
        $result['current'] = $c_size;
        $result['total'] = $l_size;
        $result['count'] = $count;
        $result['status'] = $status;

        return $result;
    }
}
