<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Admin\AdminController;
use App\Traits\FrontSetting;

class FrontController extends AdminController
{
    use FrontSetting;
    public string $moduleName = "blog";
}
