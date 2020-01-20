<?php

namespace App\Http\Controllers\User;

use App\Models\Website;
use Illuminate\Http\Request;
use Session;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Yajra\DataTables\DataTables;

class FileController extends UserController
{
    public function index()
    {
        Session::put('file.storage', 'user');

        if (request()->wantsJson()) {
            $websites = Website::where('user_id', user()->id);

            return Datatables::of($websites)->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="checkbox" data-id="' . $row->id . '">';
            })->addColumn('storage', function ($row) {
                return $row->storageUsage();
            })->editColumn('created_at', function ($row) {
                return $row->created_at?->diffForHumans();
            })->addColumn('action', function ($row) {
                return '<a href="' . route('user.file.show', $row->id) . '" class="btn btn-outline-info btn-sm m-1 p-2 m-btn m-btn--icon">
                            <span>
                                <i class="la la-eye"></i>
                                <span>Detail</span>
                            </span>
                        </a>
                        <a href="' . route('user.file.edit', $row->id) . '" class="btn btn-outline-success btn-sm m-1	p-2 m-btn m-btn--icon">
                            <span>
                                <i class="la la-edit"></i>
                                <span>Edit</span>
                            </span>
                        </a>';
            })->rawColumns(['checkbox', 'status', 'storage', 'action'])->make(true);
        }

        return view(self::$viewDir . 'file.index');
    }

    public function show()
    {

    }

    public function edit()
    {

    }

    public function uploadStockFiles(Request $request)
    {
        $images = $request->images;

        try {
            $urls = [];
            foreach ($images as $image) {
                $media = auth()->user()->addMediaFromBase64($image['url'])
                    ->usingFileName(guid() . ".jpg")
                    ->toMediaCollection('stockImages');
                $urls[] = $media->getUrl();
            }

            return $this->jsonSuccess($urls);
        } catch (\Exception $e) {
            $this->jsonExceptionError($e);
        }
    }

    public function uploadStockVideoFiles(Request $request)
    {
        try {
            if ($request->file('video')) {
                $media = auth()->user()->addMediaFromRequest('video')
                    ->usingFileName(guid() . ".mp4")
                    ->toMediaCollection('stockVideos');
                $url = $media->getUrl();

                return $this->jsonSuccess(['url' => $url]);
            }
            $this->jsonError();
        } catch (\Exception $e) {
            $this->jsonExceptionError($e);
        }
    }

    public function getStockFiles()
    {
        $media = auth()->user()->getMedia('stockImages');

        $images = [];

        foreach ($media as $image) {
            $images[] = [
                'id' => $image->id,
                'url' => $image->getUrl(),
            ];
        }

        $logos = auth()->user()->designs();

        return $this->jsonSuccess([
            'images' => $images,
            'logos' => $logos,
        ]);
    }

    public function getStockVideoFiles()
    {
        $media = auth()->user()->getMedia('stockVideos');

        $result = [];

        foreach ($media as $image) {
            $result[] = [
                'id' => $image->id,
                'url' => $image->getUrl(),
            ];
        }

        return $this->jsonSuccess($result);
    }

    public function deleteStockFiles(Request $request)
    {
        Media::destroy($request->ids);

        return $this->jsonSuccess();
    }
}
