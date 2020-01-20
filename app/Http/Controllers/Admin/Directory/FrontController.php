<?php

namespace App\Http\Controllers\Admin\Directory;

use App\Http\Controllers\Admin\AdminController;
use App\Traits\FrontSetting;

class FrontController extends AdminController
{
    use FrontSetting;
    public string $moduleName = "directory";
}
