<?php

namespace App\Repositories;

use App\Interfaces\TemplateRepositoryInterface;
use App\Models\Builder\Template;
use App\Models\Builder\TemplateCategory;
use Illuminate\Http\Request;

class TemplateRepository implements TemplateRepositoryInterface
{
    public function getCategories()
    {
        return TemplateCategory::where('parent_id', '==', 0)
            ->with('approvedSubCategories')
            ->status(1)
            ->get(['id', 'name', 'slug']);
    }

    public function updateOne(Request $request, $id)
    {
        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'status' => isset($request->status) ? 1 : 0,
            'featured' => isset($request->featured) ? 1 : 0,
            'new' => isset($request->new) ? 1 : 0,
        ];

        $template = Template::find($id);
        $template->update($data);


        $template->clearMediaCollection('preview')
            ->addMediaFromBase64(json_decode($request->image)->output->image)
            ->usingFileName(guid() . ".jpg")
            ->toMediaCollection('preview');

        //        if ($request->file('image')) {
        //            $template->clearMediaCollection('preview')
        //                ->addMediaFromRequest('image')
        //                ->usingFileName(guid() . "." . $request->image->getClientOriginalExtension())
        //                ->toMediaCollection('preview');
        //        }
    }
}
