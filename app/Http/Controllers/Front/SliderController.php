<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Slider;

class SliderController extends Controller
{
    public function detail($id)
    {
        try {
            $slider = Slider::findorfail($id);
            if ($slider->model_type === 'url') {
                return redirect($slider->model_id);
            } else {
                $model = $slider->model;
                $slug = $slider->modelToSlug($slider->model_type);
                if ($slug == 'blogPackage') {
                    return redirect()->route('blog.package.detail', $model->slug);
                } elseif ($slug == 'blogAds') {
                    return redirect()->route('blogAds.spot', $model->slug);
                }
                if (\Route::has($slug . ".detail") && $model->slug != null) {
                    return redirect()->route($slug . ".detail", $model->slug);
                } else {
                    return back();
                }
            }
        } catch (\Exception $e) {
            return back();
        }
    }
}
