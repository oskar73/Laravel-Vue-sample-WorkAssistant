<?php

namespace App\Http\Controllers\Admin\GraphicDesign;

use App\Http\Controllers\Controller;
use App\Models\GraphicDesign\UserDesign;

class UserDesignController extends Controller
{
    public UserDesign $model;

    public function __construct(UserDesign $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            return $this->model->getAdminDataTable(request()->get("status"));
        }

        return view('admin.graphic-designs.design.user');
    }
}
