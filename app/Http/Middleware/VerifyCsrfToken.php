<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/admin/template/page/upload/cover*',
        '/admin/template/page/upload/largeImage*',
        '/admin/template/page/upload/saveImage*',
        '/admin/template/page/upload/moduleImage*',
        '/ipn/stripe',
        '/ipn/paypal',
        'uploadImage*',
        'uploadImages*',
        '/newsletter/upload-image*',
    ];
}
