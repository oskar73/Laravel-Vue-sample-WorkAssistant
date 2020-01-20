<?php

use App\Http\Controllers\User as User;
use App\Http\Controllers\User\SettingController;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::name('user.')->prefix('account')->middleware(['auth', 'verified', 'passwordCheck', 'fw-only-whitelisted'])->group(function () {
    Route::prefix('getting-started')->name('getting.started.')->group(function () {
        Route::get('/', [User\GettingStartedController::class, 'index'])->name('index');
        Route::get('/welcome', [User\GettingStartedController::class, 'welcome'])->name('welcome');
        Route::post('/username', [User\GettingStartedController::class, 'username'])->name('username');
        Route::post('/demographics', [User\GettingStartedController::class, 'demographics'])->name('demographics');
        Route::post('/time', [User\GettingStartedController::class, 'time'])->name('time');
        Route::get('/complete', [User\GettingStartedController::class, 'complete'])->name('complete');
    });
});

// remove middleware  'fw-only-whitelisted'
Route::name('user.')->prefix('account')->middleware(['auth', 'verified', 'passwordCheck', 'getting-started'])->group(function () {
    Route::get('/todo', [User\TodoController::class, 'index'])->name('todo.index');
    Route::get('/getTodoCount', [User\TodoController::class, 'getTodoCount'])->name('todo.getTodoCount');
    Route::get('/todo/{type}', [User\TodoController::class, 'detail'])->name('todo.detail');

    Route::get('/started', [User\DashboardController::class, 'started'])->name('started');
    Route::get('/dashboard', [User\DashboardController::class, 'index'])->name('dashboard');

    Route::get('domainList', [User\Domain\DomainListController::class, 'index'])->name('domainList.index');
    Route::get('domainList/show/{id}', [User\Domain\DomainListController::class, 'show'])->name('domainList.show');
    Route::get('domainList/getDetail/{id}', [User\Domain\DomainListController::class, 'getDetail'])->name('domainList.getDetail');
    Route::get('domainList/getContact/{id}', [User\Domain\DomainListController::class, 'getContact'])->name('domainList.getContact');
    Route::post('domainList/getContact/{id}', [User\Domain\DomainListController::class, 'updateContact'])->name('domainList.updateContact');
    Route::get('domainList/getHosts/{id}', [User\Domain\DomainListController::class, 'getHosts'])->name('domainList.getHosts');
    Route::post('domainList/setHosts/{id}', [User\Domain\DomainListController::class, 'setHosts'])->name('domainList.setHosts');
    Route::get('domainList/getLocked/{id}', [User\Domain\DomainListController::class, 'getLocked'])->name('domainList.getLocked');
    Route::get('domainList/switchAction/{id}', [User\Domain\DomainListController::class, 'switchAction'])->name('domainList.switchAction');
    Route::get('domainList/getDns/{id}', [User\Domain\DomainListController::class, 'getDns'])->name('domainList.getDns');
    Route::post('domainList/getDns/{id}', [User\Domain\DomainListController::class, 'updateDns'])->name('domainList.updateDns');
    Route::put('domainList/getDns/{id}', [User\Domain\DomainListController::class, 'setDefaultDns'])->name('domainList.setDefaultDns');
    Route::get('domainList/renew/{id}', [User\Domain\DomainListController::class, 'renew'])->name('domainList.renew');
    Route::get('domainList/renewConfirm', [User\Domain\DomainListController::class, 'renewConfirm'])->name('domainList.renewConfirm');

    Route::post('domainList/renewWithStripe', [User\Domain\DomainListController::class, 'renewWithStripe'])
        ->name('domainList.renewWithStripe')
        ->middleware(ProtectAgainstSpam::class);
    Route::post('domainList/renewWithPaypal', [User\Domain\DomainListController::class, 'renewWithPaypal'])
        ->name('domainList.renewWithPaypal')
        ->middleware(ProtectAgainstSpam::class);
    Route::get('domainList/renewWithPaypalExecute/{status}', [User\Domain\DomainListController::class, 'renewWithPaypalExecute'])
        ->name('domainList.renewWithPaypalExecute');

    Route::get('domain/search', [User\Domain\DomainController::class, 'search'])->name('domain.search');
    Route::post('domain/search', [User\Domain\DomainController::class, 'check'])->name('domain.check');
    Route::get('domain/loadMore', [User\Domain\DomainController::class, 'loadMore'])->name('domain.loadMore');
    Route::get('domain/duration', [User\Domain\DomainController::class, 'duration'])->name('domain.duration');
    Route::get('domain/setDuration', [User\Domain\DomainController::class, 'setDuration'])->name('domain.setDuration');
    Route::post('domain/setContact', [User\Domain\DomainController::class, 'setContact'])->name('domain.setContact');
    Route::get('domain/getConfirm', [User\Domain\DomainController::class, 'getConfirm'])->name('domain.getConfirm');
    Route::post('domain/getNow', [User\Domain\DomainController::class, 'getNow'])->name('domain.getNow');

    Route::post('domain/paywithStripe', [User\Domain\PaymentController::class, 'paywithStripe'])->name('domain.paywithStripe')
        ->middleware(ProtectAgainstSpam::class);
    Route::post('domain/paywithPaypal', [User\Domain\PaymentController::class, 'paywithPaypal'])->name('domain.paywithPaypal')
        ->middleware(ProtectAgainstSpam::class);
    Route::get('domain/paywithPaypalExecute/{status}', [User\Domain\PaymentController::class, 'paywithPaypalExecute'])->name('domain.paywithPaypalExecute');

    Route::get('domain/transfer', [User\Domain\DomainTransferController::class, 'transfer'])->name('domain.transfer');
    Route::get('domain/connect', [User\Domain\DomainTransferController::class, 'connect'])->name('domain.connect');
    Route::post('domain/connect', [User\Domain\DomainTransferController::class, 'connectPost'])
        ->middleware(ProtectAgainstSpam::class);
    Route::get('domain/disconnect', [User\Domain\DomainTransferController::class, 'disconnect'])->name('domain.disconnect');

    Route::get('blog', [User\BlogController::class, 'index'])->name('blog.index');
    Route::get('blog/create', [User\BlogController::class, 'create'])->name('blog.create');
    Route::post('blog/create', [User\BlogController::class, 'store'])->name('blog.store');
    Route::get('blog/detail/{slug}', [User\BlogController::class, 'detail'])->name('blog.detail');
    Route::get('blog/edit/{slug}', [User\BlogController::class, 'edit'])->name('blog.edit');
    Route::post('blog/edit/{slug}', [User\BlogController::class, 'update'])->name('blog.update');
    Route::get('blog/post/switchPost', [User\BlogController::class, 'switchPost'])->name('blog.switchPost');

    Route::prefix('newsletter')->name('newsletter.')->group(function () {
        Route::get('archive', [User\NewsletterController::class, 'archive'])->name('archive');
    });

    Route::get('portfolio', [User\PortfolioController::class, 'index'])->name('portfolio.index');
    Route::get('portfolio/create', [User\PortfolioController::class, 'create'])->name('portfolio.create');
    Route::get('portfolio/edit/{slug}', [User\PortfolioController::class, 'edit'])->name('portfolio.edit');
    Route::post('portfolio', [User\PortfolioController::class, 'store'])->name('portfolio.store');

    Route::get('blogAds', [User\BlogAdsController::class, 'index'])->name('blogAds.index');
    Route::get('blogAds/detail/{id}', [User\BlogAdsController::class, 'detail'])->name('blogAds.detail');
    Route::get('blogAds/edit/{id}', [User\BlogAdsController::class, 'edit'])->name('blogAds.edit');
    Route::post('blogAds/edit/{id}', [User\BlogAdsController::class, 'update'])->name('blogAds.update');
    Route::get('blogAds/tracking/{id}', [User\BlogAdsController::class, 'tracking'])->name('blogAds.tracking');
    Route::get('blogAds/getChart/{id}', [User\BlogAdsController::class, 'getChart'])->name('blogAds.getChart');

    Route::prefix('directory')->name('directory.')->group(function () {
        Route::get('/', [User\DirectoryController::class, 'index'])->name('index');
        Route::get('select', [User\DirectoryController::class, 'select'])->name('select');
        Route::get('/create/{id}', [User\DirectoryController::class, 'create'])->name('create');
        Route::post('create/{id}', [User\DirectoryController::class, 'store'])->name('store');
        Route::get('show/{slug}', [User\DirectoryController::class, 'show'])->name('show');
        Route::get('edit/{slug}', [User\DirectoryController::class, 'edit'])->name('edit');
        Route::post('edit/{slug}', [User\DirectoryController::class, 'update'])->name('update');
    });

    Route::prefix('website')->name('website.')->group(function () {
        // user.website.getting
        Route::prefix('getting-started')->name('getting.')->group(function () {
            Route::get('/', [User\WebsiteController::class, 'gettingStarted'])->name('started');
            Route::get('/getTemplates', [User\WebsiteController::class, 'getTemplates'])->name('getTemplates');
            Route::get('/templates', [User\WebsiteController::class, 'templates'])->name('templates');
            Route::get('/getDomains', [User\WebsiteController::class, 'getDomains'])->name('getDomains');
            Route::get('/previewTemplate', [User\WebsiteController::class, 'previewTemplate'])->name('previewTemplate');
            Route::get('/checkSubDomain', [User\WebsiteController::class, 'checkSubDomain'])->name('checkSubDomain');
            Route::get('/getModuleFeatures', [User\WebsiteController::class, 'getModuleFeatures'])->name('getModuleFeatures');
            Route::get('/getModules', [User\WebsiteController::class, 'getModules'])->name('getModules');
            Route::get('/modules', [User\WebsiteController::class, 'modules'])->name('modules');
            Route::get('/saveStep', [User\WebsiteController::class, 'saveStep'])->name('saveStep');
            Route::post('/submit', [User\WebsiteController::class, 'submit'])->name('submit');
            Route::get('/{id}', [User\WebsiteController::class, 'resume'])->name('resume');
            Route::post('/switchPackage', [User\WebsiteController::class, 'switchPackage'])->name('switchPackage');
            Route::get('/switchPackage/{id}/{targetId}', [User\WebsiteController::class, 'paypalSwitchPackage'])->name('paypalSwitchPackage');
            Route::get('/finish/{id}', [User\WebsiteController::class, 'finish'])->name('finish');
        });

        Route::prefix('{webId}/user-template')->name('user-template.')->group(function () {
            Route::get('/', [User\UserTemplateController::class, 'getUserTemplates'])->name('list');
            Route::post('/', [User\UserTemplateController::class, 'createUserTemplate'])->name('create');
            Route::get('/{id}', [User\UserTemplateController::class, 'getUserTemplate'])->name('get');
            Route::delete('/{id}', [User\UserTemplateController::class, 'deleteUserTemplate'])->name('delete');
        });

        Route::get('/', [User\WebsiteController::class, 'index'])->name('index');
        Route::get('/create', [User\WebsiteController::class, 'create'])->name('create');
        Route::get('/select/{id}', [User\WebsiteController::class, 'select'])->name('select');
        Route::get('/domainKeyUp', [User\WebsiteController::class, 'domainKeyUp'])->name('domainKeyUp');
        Route::post('/contact', [User\WebsiteController::class, 'contact'])->name('contact');

        Route::post('/deleteDomain', [User\WebsiteController::class, 'deleteDomain'])->name('delete');
        Route::post('/connectDomain', [User\WebsiteController::class, 'connectDomain'])->name('connectDomain');
        Route::post('/updateDomain/{id}', [User\WebsiteController::class, 'updateDomain'])->name('updateDomain');
        Route::get('/loadCustom', [User\WebsiteController::class, 'loadCustom'])->name('loadCustom');
        Route::get('/checkDns', [User\WebsiteController::class, 'checkDns'])->name('checkDns');
        Route::post('/select/{id}', [User\WebsiteController::class, 'store'])->name('store');
        Route::get('/detail/{id}', [User\WebsiteController::class, 'detail'])->name('detail');
        Route::get('/edit/{id}', [User\WebsiteController::class, 'edit'])->name('edit');
        Route::get('/editContent/{website}/{url?}', [User\WebsiteController::class, 'editContent'])->where('url', '.*')->name('editContent');
        Route::get('/preview/{id}/{url?}', [User\WebsiteController::class, 'preview'])->name('preview');
        Route::get('/getWebsiteData/{id}', [User\WebsiteController::class, 'getWebsiteData'])->name('getWebsiteData');
        Route::post('/updateData/{id}', [User\WebsiteController::class, 'updateData'])->name('updateData');

        Route::post('/addPage', [User\WebsiteController::class, 'addPage'])->name('addPage');
        Route::post('/updatePagesOrder', [User\WebsiteController::class, 'updatePagesOrder'])->name('updatePagesOrder');
        Route::post('/updatePages/{id}', [User\WebsiteController::class, 'updatePage'])->name('updatePages');
        Route::post('/duplicatePage/{id}', [User\WebsiteController::class, 'duplicatePage'])->name('duplicatePage');
        Route::post('/deletePage', [User\WebsiteController::class, 'deletePage'])->name('deletePage');
        Route::post('/activateModule/{id}', [User\WebsiteController::class, 'activateModule'])->name('activateModule');

        Route::post('/updateBasic/{id}', [User\WebsiteController::class, 'updateBasic'])->name('updateBasic');
        Route::post('/updateOwner/{id}', [User\WebsiteController::class, 'updateOwner'])->name('updateOwner');
        Route::post('/updateModule/{id}', [User\WebsiteController::class, 'updateModule'])->name('updateModule');
        Route::get('/getDomain/{id}', [User\WebsiteController::class, 'getDomain'])->name('getDomain');
        Route::get('/setPrimary/{id}', [User\WebsiteController::class, 'setPrimary'])->name('setPrimary');
        Route::get('/getBlogs', [User\WebsiteController::class, 'getBlogs'])->name('logs');
    });

    Route::prefix('theme')->name('theme.')->group(function () {
        // user.theme.category
        Route::prefix('category')->name('category.')->group(function () {
            Route::get('/', [User\Theme\ThemeCategoryController::class, 'index'])->name('index');
            Route::post('/', [User\Theme\ThemeCategoryController::class, 'store'])->name('store');
            Route::get('/switch', [User\Theme\ThemeCategoryController::class, 'switch'])->name('switch');
            Route::get('/sort', [User\Theme\ThemeCategoryController::class, 'getSort'])->name('get-sort');
            Route::post('/sort', [User\Theme\ThemeCategoryController::class, 'updateSort'])->name('update-sort');
        });

        // user.theme.item
        Route::prefix('item')->name('item.')->group(function () {
            Route::get('/', [User\Theme\ThemeItemController::class, 'index'])->name('index');
            Route::post('/', [User\Theme\ThemeItemController::class, 'store'])->name('store');
            Route::put('/', [User\Theme\ThemeItemController::class, 'update'])->name('update');
            Route::get('switch', [User\Theme\ThemeItemController::class, 'switch'])->name('switch');
            Route::post('/delete', [User\Theme\ThemeItemController::class, 'delete'])->name('delete');
        });
    });

    Route::prefix('template')->name('template.')->group(function () {
        Route::get('item', [User\Template\ItemController::class, 'index'])->name('item.index');
        Route::post('item', [User\Template\ItemController::class, 'store'])->name('item.store');
        Route::get('item/preview/{slug}/{url?}', [User\Template\ItemController::class, 'preview'])->name('item.preview');
        Route::get('item/edit/{id}', [User\Template\ItemController::class, 'edit'])->name('item.edit');
        Route::post('item/edit/{website}', [User\Template\ItemController::class, 'update'])->name('item.update');
        Route::post('item/updateTemplate/{id}', [User\Template\ItemController::class, 'updateTemplate'])->name('item.updateTemplate');
        Route::post('item/publishContent/{id}', [User\Template\ItemController::class, 'publishContent'])->name('item.publishContent');

        Route::get('item/editPages/{id}', [User\Template\ItemController::class, 'editPages'])->name('item.editPages');
        Route::get('item/getTemplateData/{id}', [User\Template\ItemController::class, 'getTemplateData'])->name('item.getTemplateData');
        Route::get('item/switch', [User\Template\ItemController::class, 'switch'])->name('item.switch');
        Route::post('item/update-theme/{id}', [User\Template\ItemController::class, 'updateWebsiteTheme'])->name('item.updateWebsiteTheme');
        Route::get('item/{id}', [User\Template\ItemController::class, 'getTemplate'])->name('item.getTemplate');

        // user.template.page.
        Route::get('page/{template_id}', [User\Template\PageController::class, 'index'])->name('page.index');
        Route::post('page/{template_id}', [User\Template\PageController::class, 'store'])->name('page.store');
        Route::post('addNewPage', [User\Template\PageController::class, 'addNewPage'])->name('page.addNewPage');
        Route::get('page/edit/{id}', [User\Template\PageController::class, 'edit'])->name('page.edit');
        Route::get('page/switch/edit', [User\Template\PageController::class, 'switch'])->name('page.switch');
        Route::get('page/editPage/{id}', [User\Template\PageController::class, 'editPage'])->name('page.editPage');
        Route::post('page/addNewPage', [User\Template\PageController::class, 'addNewPage'])->name('PageController@page.addNewPage');
        Route::post('page/clone/{id}', [User\Template\PageController::class, 'duplicatePage'])->name('page.duplicatePage');
        Route::post('page/deletePage', [User\Template\PageController::class, 'deletePage'])->name('page.deletePage');
        Route::get('page/editContent/{id}/{type}', [User\Template\PageController::class, 'editContent'])->name('page.editContent');
        Route::post('page/editContent/{id}/{type}', [User\Template\PageController::class, 'updateContent']);
        Route::post('page/update/page/{id}', [User\Template\PageController::class, 'updatePage'])->name('page.updatePage');
        Route::post('page/update/order', [User\Template\PageController::class, 'updateOrder'])->name('page.updateOrder');

        Route::get('category', [User\Template\CategoryController::class, 'index'])->name('category.index');
        Route::post('category', [User\Template\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/switch', [User\Template\CategoryController::class, 'switch'])->name('category.switch');
        Route::get('category/sort', [User\Template\CategoryController::class, 'getSort'])->name('category.sort');
        Route::post('category/sort', [User\Template\CategoryController::class, 'updateSort']);
    });


    Route::prefix('purchase')->name('purchase.')->group(function () {
        Route::get('order', [User\Purchase\OrderController::class, 'index'])->name('order.index');
        Route::get('order/detail/{id}', [User\Purchase\OrderController::class, 'detail'])->name('order.detail');
        Route::post('order/confirm', [User\Purchase\OrderController::class, 'confirm'])->name('order.confirm');

        Route::get('subscription', [User\Purchase\SubscriptionController::class, 'index'])->name('subscription.index');
        Route::get('subscription/detail/{id}', [User\Purchase\SubscriptionController::class, 'detail'])->name('subscription.detail');
        Route::post('subscription/cancel', [User\Purchase\SubscriptionController::class, 'cancel'])->name('subscription.cancel');

        Route::get('transaction', [User\Purchase\TransactionController::class, 'index'])->name('transaction.index');
        Route::get('transaction/invoice/{id}', [User\Purchase\TransactionController::class, 'invoice'])->name('transaction.invoice');
        Route::get('transaction/invoice/{id}/download', [User\Purchase\TransactionController::class, 'invoiceDownload'])->name('transaction.invoiceDownload');

        Route::get('form', [User\Purchase\FormController::class, 'index'])->name('form.index');
        Route::get('form/detail/{id}', [User\Purchase\FormController::class, 'detail'])->name('form.detail');
        Route::get('form/edit/{id}', [User\Purchase\FormController::class, 'edit'])->name('form.edit');
        Route::post('form/edit/{id}', [User\Purchase\FormController::class, 'update'])->name('form.update');
        Route::get('form/switch', [User\Purchase\FormController::class, 'switchForm'])->name('form.switch');

        Route::get('package', [User\Purchase\ProductController::class, 'package'])->name('package.index');
        Route::get('package/detail/{id}', [User\Purchase\ProductController::class, 'packageDetail'])->name('package.detail');
        Route::get('readymade', [User\Purchase\ProductController::class, 'readymade'])->name('readymade.index');
        Route::get('readymade/detail/{id}', [User\Purchase\ProductController::class, 'readymadeDetail'])->name('readymade.detail');
        Route::get('blog', [User\Purchase\ProductController::class, 'blog'])->name('blog.index');
        Route::get('blog/detail/{id}', [User\Purchase\ProductController::class, 'blogDetail'])->name('blog.detail');
        Route::get('plugin', [User\Purchase\ProductController::class, 'plugin'])->name('plugin.index');
        Route::get('plugin/detail/{id}', [User\Purchase\ProductController::class, 'pluginDetail'])->name('plugin.detail');
        Route::get('lacarte', [User\Purchase\ProductController::class, 'lacarte'])->name('lacarte.index');
        Route::get('lacarte/detail/{id}', [User\Purchase\ProductController::class, 'lacarteDetail'])->name('lacarte.detail');
        Route::get('service', [User\Purchase\ProductController::class, 'service'])->name('service.index');
        Route::get('service/detail/{id}', [User\Purchase\ProductController::class, 'serviceDetail'])->name('service.detail');
        Route::get('module', [User\Purchase\ProductController::class, 'module'])->name('module.index');
    });

    Route::prefix('appointment')->name('appointment.')->group(function () {
        //        Route::get('setting', [User\Appointment\SettingController::class, 'index'])->name('setting.index');
        //        Route::post('setting', [User\Appointment\SettingController::class, 'store'])->name('setting.store');

        Route::get('category', [User\Appointment\CategoryController::class, 'index'])->name('category.index');
        Route::get('category/create', [User\Appointment\CategoryController::class, 'create'])->name('category.create');
        Route::post('category/store', [User\Appointment\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/edit/{id}', [User\Appointment\CategoryController::class, 'edit'])->name('category.edit');
        Route::post('category/edit/{id}', [User\Appointment\CategoryController::class, 'update'])->name('category.update');
        Route::get('category/switch', [User\Appointment\CategoryController::class, 'switch'])->name('category.switch');
        Route::get('category/sort', [User\Appointment\CategoryController::class, 'getSort'])->name('category.sort');
        Route::post('category/sort', [User\Appointment\CategoryController::class, 'updateSort']);
        Route::get('category-for-builder', [User\Appointment\CategoryController::class, 'categoryForBuilder'])->name('categoryForBuilder');

        Route::get('unavailable-dates/{type}/{id}', [User\Appointment\BlockDateController::class, 'index'])->name('blockDate.index');
        Route::get('unavailable-dates/edit/{type}/{id}', [User\Appointment\BlockDateController::class, 'edit'])->name('blockDate.edit');
        Route::post('unavailable-dates/{type}/{id}', [User\Appointment\BlockDateController::class, 'store'])->name('blockDate.store');
        Route::post('unavailable-dates/delete/{type}/{id}', [User\Appointment\BlockDateController::class, 'delete'])->name('blockDate.delete');

        Route::get('/listing', [User\Appointment\ListingController::class, 'index'])->name('listing.index');
        Route::get('/listing/getData', [User\Appointment\ListingController::class, 'getData'])->name('listing.getData');
        Route::get('/listing/edit/{id}', [User\Appointment\ListingController::class, 'edit'])->name('listing.edit');
        Route::post('/listing/store', [User\Appointment\ListingController::class, 'store'])->name('listing.store');
        Route::post('/listing/approve/{id}', [User\Appointment\ListingController::class, 'update'])->name('listing.update');
        Route::get('/listing/detail/{id}', [User\Appointment\ListingController::class, 'detail'])->name('listing.detail');
        Route::get('/listing/switch', [User\Appointment\ListingController::class, 'switchListing'])->name('listing.switch');
        Route::get('/listing/allListing', [User\Appointment\ListingController::class, 'allListing'])->name('listing.allListing');

        Route::prefix('site-listing')->name('site-listing.')->group(function () {
            Route::get('/', [User\Appointment\SiteListingController::class, 'index'])->name('index');
            Route::get('/getData', [User\Appointment\SiteListingController::class, 'getData'])->name('getData');
            Route::get('/edit/{id}', [User\Appointment\SiteListingController::class, 'edit'])->name('edit');
            Route::post('/store', [User\Appointment\SiteListingController::class, 'store'])->name('store');
            Route::post('/approve/{id}', [User\Appointment\SiteListingController::class, 'update'])->name('update');
            Route::get('/detail/{id}', [User\Appointment\SiteListingController::class, 'detail'])->name('detail');
            Route::get('/switch', [User\Appointment\SiteListingController::class, 'switchListing'])->name('switch');
            Route::get('/allListing', [User\Appointment\SiteListingController::class, 'allListing'])->name('allListing');
        });
    });

    Route::prefix('product')->name('product.')->group(function () {
        Route::get('/', [User\Product\ProductController::class, 'index'])->name('index');
        Route::get('create', [User\Product\ProductController::class, 'create'])->name('create');
        Route::get('edit/{id}', [User\Product\ProductController::class, 'edit'])->name('edit');
        Route::post('store', [User\Product\ProductController::class, 'store'])->name('store');
        Route::post('update/{id}', [User\Product\ProductController::class, 'update'])->name('update');
        Route::get('switch', [User\Product\ProductController::class, 'switchListing'])->name('switch');
        Route::get('for-builder', [User\Product\ProductController::class, 'forBuilder'])->name('forBuilder');

        Route::get('size', [User\Product\SizeController::class, 'index'])->name('size.index');
        Route::post('size', [User\Product\SizeController::class, 'store'])->name('size.store');
        Route::post('/size/update/{id}', [User\Product\SizeController::class, 'update'])->name('size.update');
        Route::get('/size/switch', [User\Product\SizeController::class, 'switchListing'])->name('size.switch');

        Route::get('color', [User\Product\ColorController::class, 'index'])->name('color.index');
        Route::post('color', [User\Product\ColorController::class, 'store'])->name('color.store');
        Route::post('/color/update/{id}', [User\Product\ColorController::class, 'update'])->name('color.update');
        Route::get('/color/switch', [User\Product\ColorController::class, 'switchListing'])->name('color.switch');

        Route::get('unit', [User\Product\UnitController::class, 'index'])->name('unit.index');
        Route::post('unit', [User\Product\UnitController::class, 'store'])->name('unit.store');
        Route::post('/unit/update/{id}', [User\Product\UnitController::class, 'update'])->name('unit.update');
        Route::get('/unit/switch', [User\Product\UnitController::class, 'switchListing'])->name('unit.switch');

        Route::get('category', [User\Product\CategoryController::class, 'index'])->name('category.index');
        Route::post('category', [User\Product\CategoryController::class, 'store'])->name('category.store');
        Route::post('/category/update/{id}', [User\Product\CategoryController::class, 'update'])->name('category.update');
        Route::get('/category/switch', [User\Product\CategoryController::class, 'switchListing'])->name('category.switch');

        Route::get('sub-category', [User\Product\SubCategoryController::class, 'index'])->name('sub.category.index');
        Route::post('sub-category', [User\Product\SubCategoryController::class, 'store'])->name('sub.category.store');
        Route::post('/sub-category/update/{id}', [User\Product\SubCategoryController::class, 'update'])->name('sub.category.update');
        Route::get('/sub-category/switch', [User\Product\SubCategoryController::class, 'switchListing'])->name('sub.category.switch');

        Route::prefix('coupon')->name('coupon.')->group(function () {
            Route::get('/', [User\Product\CouponController::class, 'index'])->name('index');
            Route::post('/', [User\Product\CouponController::class, 'store'])->name('store');
            Route::get('/product', [User\Product\CouponController::class, 'product'])->name('product');
            Route::get('/edit', [User\Product\CouponController::class, 'edit'])->name('edit');
            Route::get('/switch', [User\Product\CouponController::class, 'switch'])->name('switch');
        });
    });

    Route::get('file', [User\FileController::class, 'index'])->name('file.index');
    Route::get('file/show/{id}', [User\FileController::class, 'show'])->name('file.show');
    Route::get('file/edit/{id}', [User\FileController::class, 'edit'])->name('file.edit');
    Route::post('/uploadStockFiles', [User\FileController::class, 'uploadStockFiles'])->name('uploadStockFiles');
    Route::post('/uploadStockVideoFiles', [User\FileController::class, 'uploadStockVideoFiles'])->name('uploadStockVideoFiles');
    Route::get('/getStockFiles', [User\FileController::class, 'getStockFiles'])->name('getStockFiles');
    Route::post('/deleteStockFiles', [User\FileController::class, 'deleteStockFiles'])->name('deleteStockFiles');

    Route::get('ticket', [User\TicketController::class, 'index'])->name('ticket.index');
    Route::get('ticket/create', [User\TicketController::class, 'create'])->name('ticket.create');
    Route::post('ticket/create', [User\TicketController::class, 'store'])->name('ticket.store');
    Route::get('ticket/reply/{id}', [User\TicketController::class, 'edit'])->name('ticket.edit');
    Route::get('ticket/show/{id}', [User\TicketController::class, 'show'])->name('ticket.show');
    Route::post('ticket/reply/{id}', [User\TicketController::class, 'update'])->name('ticket.update');
    Route::get('ticket/switch', [User\TicketController::class, 'switch'])->name('ticket.switch');

    Route::get('tutorial', [User\TutorialController::class, 'index'])->name('tutorial.index');
    Route::get('tutorial/getData', [User\TutorialController::class, 'getData'])->name('tutorial.getData');

    Route::get('appointment', [User\AppointmentController::class, 'index'])->name('appointment.index');
    Route::get('appointment/create', [User\AppointmentController::class, 'create'])->name('appointment.create');
    Route::get('appointment/detail/{id}', [User\AppointmentController::class, 'detail'])->name('appointment.detail');
    Route::get('appointment/edit/{id}', [User\AppointmentController::class, 'edit'])->name('appointment.edit');
    Route::get('appointment/cancel/{id}', [User\AppointmentController::class, 'cancel'])->name('appointment.cancel');
    Route::get('appointment/selectProduct', [User\AppointmentController::class, 'selectProduct'])->name('appointment.selectProduct');
    Route::get('appointment/selectCategory', [User\AppointmentController::class, 'selectCategory'])->name('appointment.selectCategory');
    Route::post('appointment/store', [User\AppointmentController::class, 'store'])->name('appointment.store');

    Route::get('note', [User\NoteController::class, 'index'])->name('note.index');
    Route::post('note', [User\NoteController::class, 'store'])->name('note.store');
    Route::get('note/toggle', [User\NoteController::class, 'toggle'])->name('note.toggle');

    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
    });

    // User logo routes
    Route::prefix('color-palettes')->name('color-palettes.')->group(function () {
        Route::get('/', [User\ColorPaletteController::class, 'index'])->name('index');
        Route::get('/{userPalette}', [User\ColorPaletteController::class, 'edit'])->name('edit');
        Route::delete('/{userPalette}', [User\ColorPaletteController::class, 'delete'])->name('delete');
        Route::get('create/{type}', [User\ColorPaletteController::class, 'create'])->name('create');
        Route::post('/{type}', [User\ColorPaletteController::class, 'store'])->name('store');
        Route::get('/sort/get/{type}', [User\ColorPaletteController::class, 'sortGet'])->name('sortGet');
        Route::post('/sort/store', [User\ColorPaletteController::class, 'sortStore'])->name('sortStore');
    });

    Route::prefix('graphics')->name('graphics.')->group(function () {
        Route::get('/', [User\GraphicDesignsController::class, 'index'])->name('index');
        Route::get('/edit/{designHash}', [User\GraphicDesignsController::class, 'editDesign'])->name('edit');
        Route::get('live', [User\GraphicDesignsController::class, 'live'])->name('live');
        Route::get('preview/{designHash}', [User\GraphicDesignsController::class, 'preview'])->name('preview');
        Route::get('download/{designHash}', [User\GraphicDesignsController::class, 'downloadDesign'])->name('download')->middleware('throttle:download');
        Route::get('download/package/{designHash}', [User\GraphicDesignsController::class, 'downloadPackage'])->name('download.package');
        Route::delete('delete/{designHash}', [User\GraphicDesignsController::class, 'delete'])->name('delete');
        Route::get('progress', [User\GraphicDesignsController::class, 'progress'])->name('progress');
    });

    Route::prefix('palettes')->group(function () {
        Route::get('{type}/categories', [User\PaletteController::class, 'categoriesView'])->name('palettes.categories.view');
        Route::get('{type}/category/switch', [User\PaletteController::class, 'categorySwitch'])->name('palettes.category.switch');
        Route::post('{type}/category', [User\PaletteController::class, 'categoryStore'])->name('palettes.category.store');
        Route::get('{type}/categories/datatable', [User\PaletteController::class, 'categoriesDataTable'])->name('palettes.categories.datatable');
        Route::get('{type}/categories/sort/view', [User\PaletteController::class, 'sortView'])->name('palettes.categories.sort.view');
        Route::post('{type}/categories/sort', [User\PaletteController::class, 'sortCategories'])->name('palettes.categories.sort');

        Route::get('{type}/palettes', [User\PaletteController::class, 'palettesView'])->name('palettes.view');
        Route::get('{type}/palettes/data', [User\PaletteController::class, 'palettesData'])->name('palettes.data');
        Route::post('{type}/palette', [User\PaletteController::class, 'paletteStore'])->name('palettes.store');
        Route::put('{type}/palette', [User\PaletteController::class, 'paletteUpdate'])->name('palettes.update');
        Route::post('{type}/palettes/sort', [User\PaletteController::class, 'palettesSort'])->name('palettes.sort');
        Route::delete('{type}/palette', [User\PaletteController::class, 'paletteDelete'])->name('palettes.delete');
    });

    Route::get('newsletterAds', [User\NewsletterAdsController::class, 'index'])->name('newsletterAds.index');
    Route::get('newsletterAds/detail/{id}', [User\NewsletterAdsController::class, 'detail'])->name('newsletterAds.detail');
    Route::get('newsletterAds/edit/{id}', [User\NewsletterAdsController::class, 'edit'])->name('newsletterAds.edit');
    Route::post('newsletterAds/update/{id}', [User\NewsletterAdsController::class, 'update'])->name('newsletterAds.update');
    Route::get('newsletterAds/tracking/{id}', [User\NewsletterAdsController::class, 'tracking'])->name('newsletterAds.tracking');
    Route::get('newsletterAds/getChart/{id}', [User\NewsletterAdsController::class, 'getChart'])->name('newsletterAds.getChart');
});
