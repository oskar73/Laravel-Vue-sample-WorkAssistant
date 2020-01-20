<?php

namespace App\Http\Controllers\Api;

use App\Models\Stockgraphix\Image;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class MediaApi
{
    const STORAGE_DISK = 's3-pub-bizinabox';

    public function uploadToS3(Request $request) {
        $file = $request->file("image");
        $fileName = guid() . '.' . $file->getClientOriginalExtension();
        $path = Storage::disk(self::STORAGE_DISK)->putFileAs('', $file, $fileName);
        $s3Url = Storage::disk(self::STORAGE_DISK)->url($fileName);
        return response()->json([
            'success' => true,
            'url' => $s3Url,
        ]);
    }

    public function stockgraphix(Request $request) {
        $query = Image::with('stock');

        // Apply search condition if a search term is provided
        if (!empty($request->input('query'))) {
            $search = $request->input('query');
            info($search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('tags', 'like', '%' . $search . '%');
            });
        }

        $images = $query->paginate($request->input('per_page'), ['*'], 'page', $request->input('page'));
        $lastPage = $images->lastPage();
        $data = [];
        foreach($images as $image) {
            $data[] = [
                'url'   =>  Storage::disk('s3-pub-stockgraphix')->url('uploads/thumbnail/'.$image->thumbnail),
                'thumb' =>  Storage::disk('s3-pub-stockgraphix')->url('uploads/small/'.$image->stock[0]->name),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'total_pages' => $lastPage
        ]);
    }
}
