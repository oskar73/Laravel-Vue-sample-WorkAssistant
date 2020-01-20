<?php

use App\Http\Controllers\Api;
use App\Http\Controllers\Auth\CustomController;
use Illuminate\Support\Facades\Route;

Route::post('/sso/user', [CustomController::class, 'ssoUser']);

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user;
    });
});


Route::prefix('v1')->name('v1.')->group(function () {

    Route::get('/loadStockFavicons', [Api\SectionBuilderApi::class,'loadStockFavicons'])->name('loadStockFavicons');
    Route::get('/loadStockLogos', [Api\SectionBuilderApi::class,'loadStockLogos'])->name('loadStockLogos');
    Route::get('/loadUserFavicons', [Api\SectionBuilderApi::class,'loadUserFavicons'])->name('loadUserFavicons');
    Route::get('/loadUserLogos', [Api\SectionBuilderApi::class,'loadUserLogos'])->name('loadUserLogos');

    Route::prefix('website/{website}')->group(function () {
        Route::get('/', [Api\WebsiteApi::class, 'getWebsite'])->name('website.get');
        Route::put('/', [Api\WebsiteApi::class, 'saveWebsite'])->name('website.save');
        Route::post('/', [Api\WebsiteApi::class, 'createWebsite'])->name('website.create');
        Route::post('/publish', [Api\WebsiteApi::class, 'publishWebsite'])->name('website.publish');

        Route::prefix('module/ecommerce')->group(function () {
            Route::get('categories', [Api\EcommerceApi::class, 'getEcommerceCategories'])->name('module.ecommerce.categories');
            Route::get('products', [Api\EcommerceApi::class, 'getEcommerceProducts'])->name('module.ecommerce.products');
            Route::get('/product/{product}', [Api\EcommerceApi::class, 'getProduct'])->name('module.ecommerce.product.get');

            Route::post('checkout', [Api\EcommerceApi::class, 'checkout'])->name('module.ecommerce.checkout');
            Route::post('checkout/success', [Api\EcommerceApi::class, 'checkoutSuccess'])->name('module.ecommerce.checkout.success');
        });

        Route::prefix('module/directory')->group(function () {
            Route::get('categories', [Api\DirectoryApi::class, 'getDirectoryCategories'])->name('module.directory.categories');
            Route::get('listings', [Api\DirectoryApi::class, 'getDirectoryListings'])->name('module.directory.listings');
        });

        Route::prefix('module/portfolio')->group(function () {
            Route::get('categories', [Api\PortfolioApi::class, 'getPortfolioCategories'])->name('module.portfolio.categories');
            Route::get('items', [Api\PortfolioApi::class, 'getPortfolioItems'])->name('module.portfolio.items');
        });

        Route::prefix('page/{page}')->group(function () {
            Route::get('/', [Api\PageApi::class, 'getPage'])->name('page.get');
            Route::put('/', [Api\PageApi::class, 'savePage'])->name('page.save');
        });

        Route::prefix('template/{page}')->group(function () {
            Route::get('/', [Api\PageApi::class, 'getTemplate'])->name('template.get');
            Route::put('/', [Api\PageApi::class, 'saveTemplate'])->name('template.save');
        });
    });
});

Route::prefix('webhook')->group(function () {
    Route::post('stripe/account', [Api\WebhookController::class, 'stripeAccount']);
    Route::post('stripe/connect', [Api\WebhookController::class, 'stripeConnect']);
    Route::post('paypal', [Api\WebhookController::class, 'paypalWebhook']);
});

Route::prefix('media')->name('media.')->group(function () {
    Route::post('upload-to-s3', [Api\MediaApi::class, 'uploadToS3'])->name('uploadToS3');
    Route::post('stockgraphix', [Api\MediaApi::class, 'stockgraphix'])->name('stockgraphix');
});
