<?php

namespace App\Http\Controllers\Admin\Template;

use App\Http\Controllers\Controller;
use App\Traits\FrontSetting;

class FrontController extends Controller
{
    use FrontSetting;
    public string $moduleName = "templates";
}
