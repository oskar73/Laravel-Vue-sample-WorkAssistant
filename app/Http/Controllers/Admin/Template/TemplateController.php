<?php

namespace App\Http\Controllers\Admin\Template;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Builder\TemplateItem;

class TemplateController extends AdminController
{
    public function __construct(TemplateItem $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return view(self::$viewDir.'templates.template');
    }
}
