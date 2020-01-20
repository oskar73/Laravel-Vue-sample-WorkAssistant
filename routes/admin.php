<?php

use App\Http\Controllers\Admin as Admin;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::name('admin.')->prefix('admin')->middleware('fw-only-whitelisted')->group(function () {
    Route::get('/todo', [Admin\TodoController::class, 'index'])->name('todo.index');
    Route::get('/getTodoCount', [Admin\TodoController::class, 'getTodoCount'])->name('todo.getTodoCount');
    Route::get('/todo/{type}', [Admin\TodoController::class, 'detail'])->name('todo.detail');

    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/getTitle', [Admin\DashboardController::class, 'getTitle'])->name('getTitle');
    Route::post('/saveTitle', [Admin\DashboardController::class, 'saveTitle'])->name('saveTitle');
    Route::get('/test/{name}', [Admin\DashboardController::class, 'test'])->name('test');
    Route::get('/selectUser', [Admin\DashboardController::class, 'selectUser'])->name('selectUser');
    Route::get('/dashboard/analytics', [Admin\DashboardController::class, 'analytics'])->name('dashboard.analytics');
    Route::post('/dashboard/analytics', [Admin\DashboardController::class, 'submitAnalytics'])->name('dashboard.analytics.submit');
    Route::put('/dashboard/analytics', [Admin\DashboardController::class, 'revokeAnalytics'])->name('dashboard.analytics.revoke');
    Route::get('/dashboard/getCardData', [Admin\DashboardController::class, 'getCardData'])->name('dashboard.getCardData');

    Route::name('userManage.')->prefix('userManage')->group(function () {
        Route::get('/', [Admin\UserManageController::class, 'index'])->name('index');
        Route::get('/getLogin', [Admin\UserManageController::class, 'getLogin'])->name('getLogin');
        Route::get('/create', [Admin\UserManageController::class, 'create'])->name('create');
        Route::get('/switchStatus', [Admin\UserManageController::class, 'switchStatus'])->name('switchStatus');
        Route::post('/create', [Admin\UserManageController::class, 'store'])->name('store');
        Route::get('/detail/{id}', [Admin\UserManageController::class, 'detail'])->name('detail');
        Route::get('/edit/{id}', [Admin\UserManageController::class, 'edit'])->name('edit');
        Route::post('/updateProfile/{id}', [Admin\UserManageController::class, 'updateProfile'])->name('updateProfile');
        Route::post('/updatePassword/{id}', [Admin\UserManageController::class, 'updatePassword'])->name('updatePassword');
        Route::delete('/delete/{user}', [Admin\UserManageController::class, 'delete'])->name('delete');
    });

    // admin.blog.
    Route::prefix('blog')->name('blog.')->group(function () {

        // admin.blog.front
        Route::get('front', [Admin\Blog\FrontController::class, 'index'])->name('front.index');
        Route::post('front', [Admin\Blog\FrontController::class, 'store'])->name('front.store');

        // admin.blog.setting.
        Route::get('setting', [Admin\Blog\SettingController::class, 'index'])->name('setting.index');
        Route::post('setting', [Admin\Blog\SettingController::class, 'store'])->name('setting.store');

        // admin.blog.package.
        Route::get('package', [Admin\Blog\PackageController::class, 'index'])->name('package.index');
        Route::get('package/switch', [Admin\Blog\PackageController::class, 'switch'])->name('package.switch');
        Route::get('package/sort', [Admin\Blog\PackageController::class, 'getSort'])->name('package.sort');
        Route::post('package/sort', [Admin\Blog\PackageController::class, 'updateSort']);
        Route::get('package/create', [Admin\Blog\PackageController::class, 'create'])->name('package.create');
        Route::post('package/create', [Admin\Blog\PackageController::class, 'store'])->name('package.store');
        Route::get('package/edit/{id}', [Admin\Blog\PackageController::class, 'edit'])->name('package.edit');
        Route::post('package/edit/{id}', [Admin\Blog\PackageController::class, 'update'])->name('package.update');
        Route::post('package/updateMeetingForm/{id}', [Admin\Blog\PackageController::class, 'updateMeetingForm'])->name('package.updateMeetingForm');
        Route::post('package/createPrice/{id}', [Admin\Blog\PackageController::class, 'createPrice'])->name('package.createPrice');
        Route::delete('package/deletePrice/{id}', [Admin\Blog\PackageController::class, 'deletePrice'])->name('package.deletePrice');

        // admin.blog.category.
        Route::get('category', [Admin\Blog\CategoryController::class, 'index'])->name('category.index');
        Route::post('category', [Admin\Blog\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/switch', [Admin\Blog\CategoryController::class, 'switch'])->name('category.switch');
        Route::get('category/sort', [Admin\Blog\CategoryController::class, 'getSort'])->name('category.sort');
        Route::post('category/sort', [Admin\Blog\CategoryController::class, 'updateSort']);

        // admin.blog.tag.
        Route::get('tag', [Admin\Blog\TagController::class, 'index'])->name('tag.index');
        Route::post('tag', [Admin\Blog\TagController::class, 'store'])->name('tag.store');
        Route::get('tag/switch', [Admin\Blog\TagController::class, 'switch'])->name('tag.switch');

        // admin.blog.post.
        Route::get('post', [Admin\Blog\PostController::class, 'index'])->name('post.index');
        Route::get('post/create', [Admin\Blog\PostController::class, 'create'])->name('post.create');
        Route::post('post/create', [Admin\Blog\PostController::class, 'store'])->name('post.store');
        Route::get('post/import', [Admin\Blog\PostController::class, 'importView'])->name('post.importView');
        Route::post('post/import/view', [Admin\Blog\PostController::class, 'importPageView'])->name('post.importPageView');
        Route::post('post/import', [Admin\Blog\PostController::class, 'import'])->name('post.import');
        Route::get('post/show/{id}', [Admin\Blog\PostController::class, 'show'])->name('post.show');
        Route::get('post/edit/{id}', [Admin\Blog\PostController::class, 'edit'])->name('post.edit');
        Route::post('post/edit/{id}', [Admin\Blog\PostController::class, 'update'])->name('post.update');
        Route::get('post/switchPost', [Admin\Blog\PostController::class, 'switchPost'])->name('post.switchPost');

        // admin.blog.comment
        Route::get('comment', [Admin\Blog\CommentController::class, 'index'])->name('comment.index');
        Route::get('comment/show/{id}', [Admin\Blog\CommentController::class, 'show'])->name('comment.show');
        Route::get('comment/edit/{id}', [Admin\Blog\CommentController::class, 'edit'])->name('comment.edit');
        Route::post('comment/edit/{id}', [Admin\Blog\CommentController::class, 'update'])->name('comment.update');
        Route::get('comment/switchComment', [Admin\Blog\CommentController::class, 'switchComment'])->name('comment.switchComment');

        Route::get('author', [Admin\Blog\AuthorController::class, 'index'])->name('author.index');
    });

    Route::prefix('blogAds')->name('blogAds.')->group(function () {
        Route::get('type', [Admin\BlogAds\TypeController::class, 'index'])->name('type.index');
        Route::post('type', [Admin\BlogAds\TypeController::class, 'store'])->name('type.store');
        Route::get('type/switch', [Admin\BlogAds\TypeController::class, 'switch'])->name('type.switch');

        Route::get('position', [Admin\BlogAds\PositionController::class, 'index'])->name('position.index');
        Route::post('position', [Admin\BlogAds\PositionController::class, 'store'])->name('position.store');
        Route::get('position/switch', [Admin\BlogAds\PositionController::class, 'switchPosition'])->name('position.switch');

        Route::get('spot', [Admin\BlogAds\SpotController::class, 'index'])->name('spot.index');
        Route::get('spot/create', [Admin\BlogAds\SpotController::class, 'create'])->name('spot.create');
        Route::post('spot/create', [Admin\BlogAds\SpotController::class, 'store'])->name('spot.store');
        Route::get('spot/switch', [Admin\BlogAds\SpotController::class, 'switchSpot'])->name('spot.switch');
        Route::get('spot/getPosition', [Admin\BlogAds\SpotController::class, 'getPosition'])->name('spot.getPosition');
        Route::get('spot/edit/{id}', [Admin\BlogAds\SpotController::class, 'edit'])->name('spot.edit');
        Route::post('spot/edit/{id}', [Admin\BlogAds\SpotController::class, 'update'])->name('spot.update');
        Route::post('spot/createPrice/{id}', [Admin\BlogAds\SpotController::class, 'createPrice'])->name('spot.createPrice');
        Route::delete('spot/deletePrice/{id}', [Admin\BlogAds\SpotController::class, 'deletePrice'])->name('spot.deletePrice');
        Route::post('spot/updateListing/{id}', [Admin\BlogAds\SpotController::class, 'updateListing'])->name('spot.updateListing');

        Route::get('listing', [Admin\BlogAds\ListingController::class, 'index'])->name('listing.index');
        Route::get('listing/select', [Admin\BlogAds\ListingController::class, 'select'])->name('listing.select');
        Route::get('listing/create/{slug}', [Admin\BlogAds\ListingController::class, 'create'])->name('listing.create');
        Route::post('listing/create/{slug}', [Admin\BlogAds\ListingController::class, 'store'])->name('listing.store');
        Route::get('listing/show/{id}', [Admin\BlogAds\ListingController::class, 'show'])->name('listing.show');
        Route::get('listing/edit/{id}', [Admin\BlogAds\ListingController::class, 'edit'])->name('listing.edit');
        Route::post('listing/edit/{id}', [Admin\BlogAds\ListingController::class, 'update'])->name('listing.update');
        Route::get('listing/tracking/{id}', [Admin\BlogAds\ListingController::class, 'tracking'])->name('listing.tracking');
        Route::get('listing/getChart/{id}', [Admin\BlogAds\ListingController::class, 'getChart'])->name('listing.getChart');
        Route::post('listing/updateStatus/{id}', [Admin\BlogAds\ListingController::class, 'updateStatus'])->name('listing.updateStatus');
        Route::get('listing/switch', [Admin\BlogAds\ListingController::class, 'switchListing'])->name('listing.switch');

        Route::get('calendar', [Admin\BlogAds\CalendarController::class, 'index'])->name('calendar.index');
        Route::get('calendar/spot/{id}', [Admin\BlogAds\CalendarController::class, 'spot'])->name('calendar.spot');
        Route::get('calendar/events', [Admin\BlogAds\CalendarController::class, 'events'])->name('calendar.events');
    });

    Route::prefix('directory')->name('directory.')->group(function () {
        Route::get('front', [Admin\Directory\FrontController::class, 'index'])->name('front.index');
        Route::post('front', [Admin\Directory\FrontController::class, 'store'])->name('front.store');

        Route::get('setting', [Admin\Directory\SettingController::class, 'index'])->name('setting.index');
        Route::post('setting', [Admin\Directory\SettingController::class, 'store'])->name('setting.store');

        Route::get('category', [Admin\Directory\CategoryController::class, 'index'])->name('category.index');
        Route::post('category', [Admin\Directory\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/switch', [Admin\Directory\CategoryController::class, 'switch'])->name('category.switch');
        Route::get('category/sort', [Admin\Directory\CategoryController::class, 'getSort'])->name('category.sort');
        Route::post('category/sort', [Admin\Directory\CategoryController::class, 'updateSort']);

        Route::get('tag', [Admin\Directory\TagController::class, 'index'])->name('tag.index');
        Route::post('tag', [Admin\Directory\TagController::class, 'store'])->name('tag.store');
        Route::get('tag/switch', [Admin\Directory\TagController::class, 'switch'])->name('tag.switch');

        Route::get('package', [Admin\Directory\PackageController::class, 'index'])->name('package.index');
        Route::get('package/switch', [Admin\Directory\PackageController::class, 'switch'])->name('package.switch');
        Route::get('package/sort', [Admin\Directory\PackageController::class, 'getSort'])->name('package.sort');
        Route::post('package/sort', [Admin\Directory\PackageController::class, 'updateSort']);

        Route::get('package/create', [Admin\Directory\PackageController::class, 'create'])->name('package.create');
        Route::post('package/create', [Admin\Directory\PackageController::class, 'store'])->name('package.store');
        Route::get('package/edit/{id}', [Admin\Directory\PackageController::class, 'edit'])->name('package.edit');
        Route::post('package/edit/{id}', [Admin\Directory\PackageController::class, 'update'])->name('package.update');

        Route::post('package/updateMeetingForm/{id}', [Admin\Directory\PackageController::class, 'updateMeetingForm'])->name('package.updateMeetingForm');
        Route::post('package/createPrice/{id}', [Admin\Directory\PackageController::class, 'createPrice'])->name('package.createPrice');
        Route::delete('package/deletePrice/{id}', [Admin\Directory\PackageController::class, 'deletePrice'])->name('package.deletePrice');

        Route::get('listing', [Admin\Directory\ListingController::class, 'index'])->name('listing.index');
        Route::get('listing/create', [Admin\Directory\ListingController::class, 'create'])->name('listing.create');
        Route::post('listing/create', [Admin\Directory\ListingController::class, 'store'])->name('listing.store');
        Route::get('listing/preview/{slug}', [Admin\Directory\ListingController::class, 'preview'])->name('listing.preview');
        Route::get('listing/show/{id}', [Admin\Directory\ListingController::class, 'show'])->name('listing.show');
        Route::get('listing/edit/{id}', [Admin\Directory\ListingController::class, 'edit'])->name('listing.edit');
        Route::get('listing/deny/{id}', [Admin\Directory\ListingController::class, 'deny'])->name('listing.deny');
        Route::get('listing/approve/{id}', [Admin\Directory\ListingController::class, 'approve'])->name('listing.approve');
        Route::get('listing/switch', [Admin\Directory\ListingController::class, 'switch'])->name('listing.switch');
    });

    Route::prefix('email')->name('email.')->group(function () {
        Route::get('package', [Admin\Email\PackageController::class, 'index'])->name('package.index');
        Route::get('package/create', [Admin\Email\PackageController::class, 'create'])->name('package.create');
        Route::post('package/create', [Admin\Email\PackageController::class, 'store'])->name('package.store');
        Route::get('package/edit/{id}', [Admin\Email\PackageController::class, 'edit'])->name('package.edit');
        Route::post('package/edit/{id}', [Admin\Email\PackageController::class, 'update'])->name('package.update');
        Route::post('package/updateMeeting/{id}', [Admin\Email\PackageController::class, 'updateMeeting'])->name('package.updateMeeting');
        Route::post('package/createPrice/{id}', [Admin\Email\PackageController::class, 'createPrice'])->name('package.createPrice');
        Route::post('package/updatePrice/{id}', [Admin\Email\PackageController::class, 'updatePrice'])->name('package.updatePrice');
        Route::delete('package/deletePrice/{id}', [Admin\Email\PackageController::class, 'deletePrice'])->name('package.deletePrice');
        Route::get('package/switch', [Admin\Email\PackageController::class, 'switch'])->name('package.switch');
        Route::get('package/sort', [Admin\Email\PackageController::class, 'getSort'])->name('package.sort');
        Route::post('package/sort', [Admin\Email\PackageController::class, 'updateSort']);

        Route::get('category', [Admin\Email\CategoryController::class, 'index'])->name('category.index');
        Route::post('category', [Admin\Email\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/switch', [Admin\Email\CategoryController::class, 'switch'])->name('category.switch');
        Route::get('category/sort', [Admin\Email\CategoryController::class, 'getSort'])->name('category.sort');
        Route::post('category/sort', [Admin\Email\CategoryController::class, 'updateSort']);

        Route::get('/template', [Admin\Email\TemplateController::class, 'index'])->name('template.index');
        Route::get('/template/create', [Admin\Email\TemplateController::class, 'create'])->name('template.create');
        Route::post('/template/create', [Admin\Email\TemplateController::class, 'store'])->name('template.store');
        Route::get('/template/edit/{id}', [Admin\Email\TemplateController::class, 'edit'])->name('template.edit');
        Route::post('/template/updateBody/{id}', [Admin\Email\TemplateController::class, 'updateBody'])->name('template.updateBody');
        Route::get('/template/switch', [Admin\Email\TemplateController::class, 'switch'])->name('template.switch');

        Route::get('/campaign', [Admin\Email\CampaignController::class, 'index'])->name('campaign.index');
        Route::get('/campaign/create', [Admin\Email\CampaignController::class, 'create'])->name('campaign.create');
        Route::post('/campaign/create', [Admin\Email\CampaignController::class, 'store'])->name('campaign.store');
        Route::get('/campaign/edit/{id}', [Admin\Email\CampaignController::class, 'edit'])->name('campaign.edit');
        Route::get('/campaign/show/{id}', [Admin\Email\CampaignController::class, 'show'])->name('campaign.show');
        Route::get('/campaign/switch', [Admin\Email\CampaignController::class, 'switch'])->name('campaign.switch');
        Route::get('/campaign/sendNow', [Admin\Email\CampaignController::class, 'sendNow'])->name('campaign.sendNow');
        Route::get('/campaign/getCategory', [Admin\Email\CampaignController::class, 'getCategory'])->name('campaign.getCategory');
        Route::get('/campaign/getTemplate', [Admin\Email\CampaignController::class, 'getTemplate'])->name('campaign.getTemplate');

        Route::get('subscriber', [Admin\Email\SubscriberController::class, 'index'])->name('subscriber.index');
        Route::get('subscriber/switch', [Admin\Email\SubscriberController::class, 'switch'])->name('subscriber.switch');
    });

    Route::get('domainTld', [Admin\Domain\DomainTldsController::class, 'index'])->name('domainTld.index');
    Route::get('domainTld/switch', [Admin\Domain\DomainTldsController::class, 'switch'])->name('domainTld.switch');
    Route::get('domainTld/get', [Admin\Domain\DomainTldsController::class, 'get'])->name('domainTld.get');
    Route::get('domainTld/show/{id}', [Admin\Domain\DomainTldsController::class, 'show'])->name('domainTld.show');
    Route::get('domainTld/edit/{id}', [Admin\Domain\DomainTldsController::class, 'edit'])->name('domainTld.edit');

    Route::get('domainPrice/switch', [Admin\Domain\DomainPricesController::class, 'switch'])->name('domainPrice.switch');
    Route::get('domainPrice/get', [Admin\Domain\DomainPricesController::class, 'get'])->name('domainPrice.get');
    Route::put('domainPrice/update/{id}', [Admin\Domain\DomainPricesController::class, 'update'])->name('domainPrice.update');

    Route::get('domain/search', [Admin\Domain\DomainController::class, 'search'])->name('domain.search');
    Route::post('domain/search', [Admin\Domain\DomainController::class, 'check'])->name('domain.check');
    Route::get('domain/loadMore', [Admin\Domain\DomainController::class, 'loadMore'])->name('domain.loadMore');
    Route::get('domain/duration', [Admin\Domain\DomainController::class, 'duration'])->name('domain.duration');
    Route::get('domain/setDuration', [Admin\Domain\DomainController::class, 'setDuration'])->name('domain.setDuration');
    Route::post('domain/setContact', [Admin\Domain\DomainController::class, 'setContact'])->name('domain.setContact');
    Route::post('domain/getNow', [Admin\Domain\DomainController::class, 'getNow'])->name('domain.getNow');

    Route::get('domainList', [Admin\Domain\DomainListController::class, 'index'])->name('domainList.index');
    Route::get('domainList/show/{id}', [Admin\Domain\DomainListController::class, 'show'])->name('domainList.show');
    Route::get('domainList/getDetail/{id}', [Admin\Domain\DomainListController::class, 'getDetail'])->name('domainList.getDetail');
    Route::get('domainList/getContact/{id}', [Admin\Domain\DomainListController::class, 'getContact'])->name('domainList.getContact');
    Route::post('domainList/getContact/{id}', [Admin\Domain\DomainListController::class, 'updateContact'])->name('domainList.updateContact');
    Route::get('domainList/getHosts/{id}', [Admin\Domain\DomainListController::class, 'getHosts'])->name('domainList.getHosts');
    Route::post('domainList/setHosts/{id}', [Admin\Domain\DomainListController::class, 'setHosts'])->name('domainList.setHosts');
    Route::get('domainList/getLocked/{id}', [Admin\Domain\DomainListController::class, 'getLocked'])->name('domainList.getLocked');
    Route::get('domainList/switchAction/{id}', [Admin\Domain\DomainListController::class, 'switchAction'])->name('domainList.switchAction');
    Route::get('domainList/getDns/{id}', [Admin\Domain\DomainListController::class, 'getDns'])->name('domainList.getDns');
    Route::post('domainList/getDns/{id}', [Admin\Domain\DomainListController::class, 'updateDns'])->name('domainList.updateDns');
    Route::put('domainList/getDns/{id}', [Admin\Domain\DomainListController::class, 'setDefaultDns'])->name('domainList.setDefaultDns');
    Route::get('domainList/set-default-dns/{id}', [Admin\Domain\DomainListController::class, 'setDefaultDns'])->name('domainList.setDefaultDns');
    Route::get('domainList/renew/{id}', [Admin\Domain\DomainListController::class, 'renew'])->name('domainList.renew');
    Route::get('domainList/renewConfirm', [Admin\Domain\DomainListController::class, 'renewConfirm'])->name('domainList.renewConfirm');
    Route::post('domainList/renewNow', [Admin\Domain\DomainListController::class, 'renewNow'])->name('domainList.renewNow');

    Route::get('domain/transfer', [Admin\Domain\DomainTransferController::class, 'transfer'])->name('domain.transfer');
    Route::get('domain/connect', [Admin\Domain\DomainTransferController::class, 'connect'])->name('domain.connect');
    Route::get('domain/connectList', [Admin\Domain\DomainTransferController::class, 'connectList'])->name('domain.connectList');
    Route::post('domain/connect', [Admin\Domain\DomainTransferController::class, 'connectPost'])
        ->middleware(ProtectAgainstSpam::class);
    Route::get('domain/disconnect', [Admin\Domain\DomainTransferController::class, 'disconnect'])->name('domain.disconnect');

    // admin.file.
    Route::prefix('file')->name('file.')->group(function () {
        Route::get('storage', [Admin\File\StorageController::class, 'index'])->name('storage.index');
        Route::get('storage/getData', [Admin\File\StorageController::class, 'getData'])->name('storage.getData');
        Route::get('storage/loadSize', [Admin\File\StorageController::class, 'loadSize'])->name('main.loadSize');

        Route::get('website', [Admin\File\WebsiteController::class, 'index'])->name('website.index');
        Route::get('website/show/{id}', [Admin\File\WebsiteController::class, 'show'])->name('website.show');
        Route::get('website/edit/{id}', [Admin\File\WebsiteController::class, 'edit'])->name('website.edit');
    });

    // admin.template
    Route::prefix('template')->name('template.')->group(function () {
        Route::get('front', [Admin\Template\FrontController::class, 'index'])->name('front.index');
        Route::post('front', [Admin\Template\FrontController::class, 'store'])->name('front.store');

        // admin.template.category.
        Route::get('category', [Admin\Template\CategoryController::class, 'index'])->name('category.index');
        Route::post('category', [Admin\Template\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/switch', [Admin\Template\CategoryController::class, 'switch'])->name('category.switch');
        Route::get('category/sort', [Admin\Template\CategoryController::class, 'getSort'])->name('category.sort');
        Route::post('category/sort', [Admin\Template\CategoryController::class, 'updateSort']);

        // admin.template.header.
        Route::get('header', [Admin\Template\HeaderController::class, 'index'])->name('header.index');
        Route::post('header', [Admin\Template\HeaderController::class, 'store'])->name('header.store');
        Route::get('header/switch', [Admin\Template\HeaderController::class, 'switch'])->name('header.switch');
        Route::get('header/edit/{id}', [Admin\Template\HeaderController::class, 'edit'])->name('header.edit');

        // admin.template.footer.
        Route::get('footer', [Admin\Template\FooterController::class, 'index'])->name('footer.index');
        Route::post('footer', [Admin\Template\FooterController::class, 'store'])->name('footer.store');
        Route::get('footer/switch', [Admin\Template\FooterController::class, 'switch'])->name('footer.switch');
        Route::get('footer/edit/{id}', [Admin\Template\FooterController::class, 'edit'])->name('footer.edit');

        // admin.template.section.
        Route::get('section', [Admin\Template\SectionController::class, 'index'])->name('section.index');
        Route::post('section/category', [Admin\Template\SectionController::class, 'categoryStore'])->name('section.category.store');
        Route::post('section/itemStore/{id}', [Admin\Template\SectionController::class, 'itemStore'])->name('section.item.store');
        Route::post('section/itemUpdate/{id}', [Admin\Template\SectionController::class, 'itemUpdate'])->name('section.item.update');
        Route::get('section/category/switch', [Admin\Template\SectionController::class, 'switch'])->name('section.category.switch');
        Route::get('section/item/switch', [Admin\Template\SectionController::class, 'itemSwitch'])->name('section.item.switch');
        Route::get('section/create/{id}', [Admin\Template\SectionController::class, 'create'])->name('section.create');
        Route::get('section/edit/{id}', [Admin\Template\SectionController::class, 'edit'])->name('section.edit');
        Route::post('section/add', [Admin\Template\SectionController::class, 'add'])->name('section.add');

        // admin.template.item.
        Route::get('item', [Admin\Template\ItemController::class, 'index'])->name('item.index');
        Route::post('item', [Admin\Template\ItemController::class, 'store'])->name('item.store');
        Route::get('item/switch', [Admin\Template\ItemController::class, 'switch'])->name('item.switch');
        Route::get('item/edit/{id}', [Admin\Template\ItemController::class, 'edit'])->name('item.edit');
        Route::post('item/edit/{template}', [Admin\Template\ItemController::class, 'update'])->name('item.update');
        Route::post('item/updateTemplate/{id}', [Admin\Template\ItemController::class, 'updateTemplate'])->name('item.updateTemplate');
        Route::get('item/preview/{slug}/{url?}', [Admin\Template\ItemController::class, 'preview'])->name('item.preview');
        Route::get('item/getTemplatePreviewData/{id}', [Admin\Template\ItemController::class, 'getTemplatePreviewData'])->name('item.getTemplatePreviewData');

        Route::post('item/publishContent/{id}', [Admin\Template\ItemController::class, 'publishContent'])->name('item.publishContent');

        // template builder routes
        Route::get('item/editPages/{template}/{url?}', [Admin\Template\ItemController::class, 'editPages'])->name('item.editPages');
        Route::get('item/getTemplateData/{id}', [Admin\Template\ItemController::class, 'getTemplateData'])->name('item.getTemplateData');
        Route::get('item/{id}', [Admin\Template\ItemController::class, 'getTemplate'])->name('item.getTemplate');
        Route::post('item/uploadPreviewUrl/{id}', [Admin\Template\ItemController::class, 'uploadPreviewUrl'])->name('item.uploadPreviewImage');
        Route::post('item/update-theme/{id}', [Admin\Template\ItemController::class, 'updateTemplateTheme'])->name('item.updateTemplateTheme');

        // admin.template.page.
        Route::get('page/{template_id}', [Admin\Template\PageController::class, 'index'])->name('page.index');
        Route::post('page/add', [Admin\Template\PageController::class, 'addNewPage'])->name('page.addNewPage');
        Route::get('page/edit/{id}', [Admin\Template\PageController::class, 'edit'])->name('page.edit');
        Route::get('page/switch/edit', [Admin\Template\PageController::class, 'switch'])->name('page.switch');
        Route::get('page/editPage/{id}', [Admin\Template\PageController::class, 'editPage'])->name('page.editPage');
        Route::post('page/clone/{page}', [Admin\Template\PageController::class, 'duplicatePage'])->name('page.duplicatePage');
        Route::delete('page/{page}', [Admin\Template\PageController::class, 'deletePage'])->name('page.delete');
        Route::get('page/editContent/{id}/{type}', [Admin\Template\PageController::class, 'editContent'])->name('page.editContent');
        Route::post('page/editContent/{id}/{type}', [Admin\Template\PageController::class, 'updateContent']);
        Route::post('page/update/page/{id}', [Admin\Template\PageController::class, 'updatePage'])->name('page.updatePage');
        Route::post('page/update/order', [Admin\Template\PageController::class, 'updateOrder'])->name('page.updateOrder');
        Route::post('page/{template_id}', [Admin\Template\PageController::class, 'store'])->name('page.store');

        // admin.template.page.
        Route::post('/page/upload/cover/{page_id}', [Admin\Template\PageController::class, 'uploadCover'])->name('page.uploadCover');
        Route::post('/page/upload/largeImage/{page_id}', [Admin\Template\PageController::class, 'uploadLarageImage'])->name('page.largeImage');
        Route::post('/page/upload/moduleImage/{page_id}', [Admin\Template\PageController::class, 'uploadModuleImage'])->name('page.moduleImage');
        Route::post('/page/upload/saveImage/{page_id}', [Admin\Template\PageController::class, 'storeImage'])->name('page.saveImage');
    });

    Route::prefix('website')->name('website.')->group(function () {
        Route::get('list', [Admin\Website\ListController::class, 'index'])->name('list.index');
        Route::get('list/create', [Admin\Website\ListController::class, 'create'])->name('list.create');
        Route::post('list/create', [Admin\Website\ListController::class, 'store'])->name('list.store');
        Route::get('list/show/{id}', [Admin\Website\ListController::class, 'show'])->name('list.show');
        Route::get('list/edit/{id}', [Admin\Website\ListController::class, 'edit'])->name('list.edit');
        Route::get('list/switch', [Admin\Website\ListController::class, 'switch'])->name('list.switch');
        Route::post('list/connectDomain', [Admin\Website\ListController::class, 'connectDomain'])->name('list.connectDomain');
        Route::post('list/updateDomain/{id}', [Admin\Website\ListController::class, 'updateDomain'])->name('list.updateDomain');
        Route::post('list/updateModule/{id}', [Admin\Website\ListController::class, 'updateModule'])->name('list.updateModule');
        Route::get('list/loadCustom', [Admin\Website\ListController::class, 'loadCustom'])->name('list.loadCustom');
        Route::get('list/checkDns', [Admin\Website\ListController::class, 'checkDns'])->name('list.checkDns');
        Route::get('list/setPrimary/{id}', [Admin\Website\ListController::class, 'setPrimary'])->name('list.setPrimary');
        Route::post('list/profileUpdate/{id}', [Admin\Website\ListController::class, 'profileUpdate'])->name('list.profileUpdate');
        Route::post('list/basicUpdate/{id}', [Admin\Website\ListController::class, 'basicUpdate'])->name('list.basicUpdate');

        // admin.website.
        Route::get('/getting-started', [Admin\Website\WebsiteController::class, 'gettingStarted'])->name('getting.started');
        Route::get('/getting-started/getTemplates', [Admin\Website\WebsiteController::class, 'getTemplates'])->name('getting.getTemplates');
        Route::get('/getting-started/getDomains', [Admin\Website\WebsiteController::class, 'getDomains'])->name('getting.getDomains');
        Route::get('/getting-started/previewTemplate', [Admin\Website\WebsiteController::class, 'previewTemplate'])->name('getting.previewTemplate');
        Route::get('/getting-started/checkSubDomain', [Admin\Website\WebsiteController::class, 'checkSubDomain'])->name('getting.checkSubDomain');
        Route::get('/getting-started/getModuleFeatures', [Admin\Website\WebsiteController::class, 'getModuleFeatures'])->name('getting.getModuleFeatures');
        Route::get('/getting-started/getModules', [Admin\Website\WebsiteController::class, 'getModules'])->name('getting.getModules');
        Route::get('/getting-started/saveStep', [Admin\Website\WebsiteController::class, 'saveStep'])->name('getting.saveStep');
        Route::post('/getting-started/submit', [Admin\Website\WebsiteController::class, 'submit'])->name('getting.submit');

        Route::post('/connectDomain', [Admin\Website\WebsiteController::class, 'connectDomain'])->name('connectDomain');
        Route::get('/loadCustom', [Admin\Website\WebsiteController::class, 'loadCustom'])->name('loadCustom');

        Route::get('/edit/{id}', [Admin\Website\WebsiteController::class, 'edit'])->name('edit');
        Route::post('/updateBasic/{id}', [Admin\Website\WebsiteController::class, 'updateBasic'])->name('updateBasic');
        Route::post('/updateOwner/{id}', [Admin\Website\WebsiteController::class, 'updateOwner'])->name('updateOwner');
        Route::post('/updateModule/{id}', [Admin\Website\WebsiteController::class, 'updateModule'])->name('updateModule');
        Route::get('/getDomain/{id}', [Admin\Website\WebsiteController::class, 'getDomain'])->name('getDomain');
        Route::get('/setPrimary/{id}', [Admin\Website\WebsiteController::class, 'setPrimary'])->name('setPrimary');
        Route::get('/editContent/{website}', [Admin\Website\WebsiteController::class, 'editContent'])->name('editContent'); // fix sub url 404 issue with adding "/{type}"
        Route::get('/editContent/{website}/{type}', [Admin\Website\WebsiteController::class, 'editContent']);
        Route::get('/getWebsiteData/{id}', [Admin\Website\WebsiteController::class, 'getWebsiteData'])->name('getWebsiteData');
    });

    Route::prefix('widget')->name('widget.')->group(function () {
        Route::get('list', [Admin\WidgetController::class, 'index'])->name('index');
        Route::get('delete/{id}', [Admin\WidgetController::class, 'delete'])->name('delete');
        Route::get('delete/item/{id}', [Admin\WidgetController::class, 'deleteItem'])->name('delete.item');
        Route::post('store', [Admin\WidgetController::class, 'store'])->name('store');
        Route::post('sort', [Admin\WidgetController::class, 'sort'])->name('sort');
        Route::post('store/item', [Admin\WidgetController::class, 'storeItem'])->name('store.item');
        Route::post('sort/items', [Admin\WidgetController::class, 'sortItems'])->name('sort.items');
    });

    Route::prefix('mail')->name('mail.')->group(function () {
        Route::get('domain', [Admin\Mail\DomainController::class, 'index'])->name('domain.index');
        Route::get('domain/create', [Admin\Mail\DomainController::class, 'create'])->name('domain.create');
        Route::post('domain/create', [Admin\Mail\DomainController::class, 'store'])->name('domain.store');
        Route::get('domain/edit/{id}', [Admin\Mail\DomainController::class, 'edit'])->name('domain.edit');
        Route::post('domain/edit/{id}', [Admin\Mail\DomainController::class, 'update'])->name('domain.update');
        Route::delete('domain/delete', [Admin\Mail\DomainController::class, 'delete'])->name('domain.delete');

        Route::get('domain/{id}/accounts', [Admin\Mail\AccountController::class, 'index'])->name('account.index');
        Route::get('domain/{id}/accounts/create', [Admin\Mail\AccountController::class, 'create'])->name('account.create');
        Route::post('domain/{id}/accounts/create', [Admin\Mail\AccountController::class, 'store'])->name('account.store');
        Route::get('domain/accounts/edit/{id}', [Admin\Mail\AccountController::class, 'edit'])->name('account.edit');
        Route::post('domain/accounts/edit/{id}', [Admin\Mail\AccountController::class, 'update'])->name('account.update');
        Route::delete('domain/accounts/delete', [Admin\Mail\AccountController::class, 'delete'])->name('account.delete');
    });

    Route::prefix('purchasefollowup')->name('purchasefollowup.')->group(function () {
        Route::get('email', [Admin\PurchaseFollowup\EmailController::class, 'index'])->name('email.index');
        Route::post('email', [Admin\PurchaseFollowup\EmailController::class, 'store'])->name('email.store');
        Route::get('email/switch', [Admin\PurchaseFollowup\EmailController::class, 'switch'])->name('email.switch');

        Route::get('form', [Admin\PurchaseFollowup\FormController::class, 'index'])->name('form.index');
        Route::get('form/create', [Admin\PurchaseFollowup\FormController::class, 'create'])->name('form.create');
        Route::post('form/create', [Admin\PurchaseFollowup\FormController::class, 'store'])->name('form.store');
        Route::get('form/show/{id}', [Admin\PurchaseFollowup\FormController::class, 'show'])->name('form.show');
        Route::get('form/edit/{id}', [Admin\PurchaseFollowup\FormController::class, 'edit'])->name('form.edit');
        Route::get('form/switch', [Admin\PurchaseFollowup\FormController::class, 'switch'])->name('form.switch');
    });

    Route::prefix('portfolio')->name('portfolio.')->group(function () {
        Route::get('front', [Admin\Portfolio\FrontController::class, 'index'])->name('front.index');
        Route::post('front', [Admin\Portfolio\FrontController::class, 'store'])->name('front.store');

        Route::get('category', [Admin\Portfolio\CategoryController::class, 'index'])->name('category.index');
        Route::post('category', [Admin\Portfolio\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/switch', [Admin\Portfolio\CategoryController::class, 'switch'])->name('category.switch');
        Route::get('category/sort', [Admin\Portfolio\CategoryController::class, 'getSort'])->name('category.sort');
        Route::post('category/sort', [Admin\Portfolio\CategoryController::class, 'updateSort']);

        Route::get('item', [Admin\Portfolio\ItemController::class, 'index'])->name('item.index');
        Route::get('item/create', [Admin\Portfolio\ItemController::class, 'create'])->name('item.create');
        Route::post('item/create', [Admin\Portfolio\ItemController::class, 'store'])->name('item.store');
        Route::get('item/preview/{id}', [Admin\Portfolio\ItemController::class, 'preview'])->name('item.preview');
        Route::get('item/edit/{id}', [Admin\Portfolio\ItemController::class, 'edit'])->name('item.edit');
        Route::post('item/edit/{id}', [Admin\Portfolio\ItemController::class, 'update'])->name('item.update');
        Route::get('item/approve/{id}', [Admin\Portfolio\ItemController::class, 'approve'])->name('item.approve');
        Route::get('item/deny/{id}', [Admin\Portfolio\ItemController::class, 'deny'])->name('item.deny');
        Route::get('item/switch', [Admin\Portfolio\ItemController::class, 'switch'])->name('item.switch');
    });

    Route::prefix('service')->name('service.')->group(function () {
        Route::get('category', [Admin\Service\CategoryController::class, 'index'])->name('category.index');
        Route::post('category', [Admin\Service\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/switch', [Admin\Service\CategoryController::class, 'switch'])->name('category.switch');
        Route::get('category/sort', [Admin\Service\CategoryController::class, 'getSort'])->name('category.sort');
        Route::post('category/sort', [Admin\Service\CategoryController::class, 'updateSort']);

        Route::get('item', [Admin\Service\ItemController::class, 'index'])->name('item.index');
        Route::get('item/switch', [Admin\Service\ItemController::class, 'switch'])->name('item.switch');
        Route::get('item/create', [Admin\Service\ItemController::class, 'create'])->name('item.create');
        Route::post('item/create', [Admin\Service\ItemController::class, 'store'])->name('item.store');
        Route::get('item/edit/{id}', [Admin\Service\ItemController::class, 'edit'])->name('item.edit');
        Route::post('item/edit/{id}', [Admin\Service\ItemController::class, 'update'])->name('item.update');
        Route::post('item/updateMeetingForm/{id}', [Admin\Service\ItemController::class, 'updateMeetingForm'])->name('item.updateMeetingForm');
    });

    Route::prefix('plugin')->name('plugin.')->group(function () {
        Route::get('category', [Admin\Plugin\CategoryController::class, 'index'])->name('category.index');
        Route::post('category', [Admin\Plugin\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/switch', [Admin\Plugin\CategoryController::class, 'switch'])->name('category.switch');
        Route::get('category/sort', [Admin\Plugin\CategoryController::class, 'getSort'])->name('category.sort');
        Route::post('category/sort', [Admin\Plugin\CategoryController::class, 'updateSort']);

        Route::get('item', [Admin\Plugin\ItemController::class, 'index'])->name('item.index');
        Route::get('item/switch', [Admin\Plugin\ItemController::class, 'switch'])->name('item.switch');

        Route::get('item/create', [Admin\Plugin\ItemController::class, 'create'])->name('item.create');
        Route::post('item/create', [Admin\Plugin\ItemController::class, 'store'])->name('item.store');
        Route::get('item/edit/{id}', [Admin\Plugin\ItemController::class, 'edit'])->name('item.edit');
        Route::post('item/edit/{id}', [Admin\Plugin\ItemController::class, 'update'])->name('item.update');

        Route::post('item/updateMeetingForm/{id}', [Admin\Plugin\ItemController::class, 'updateMeetingForm'])->name('item.updateMeetingForm');
    });

    Route::prefix('lacarte')->name('lacarte.')->group(function () {
        Route::get('category', [Admin\Lacarte\CategoryController::class, 'index'])->name('category.index');
        Route::post('category', [Admin\Lacarte\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/switch', [Admin\Lacarte\CategoryController::class, 'switch'])->name('category.switch');
        Route::get('category/sort', [Admin\Lacarte\CategoryController::class, 'getSort'])->name('category.sort');
        Route::post('category/sort', [Admin\Lacarte\CategoryController::class, 'updateSort']);

        Route::get('item', [Admin\Lacarte\ItemController::class, 'index'])->name('item.index');
        Route::get('item/switch', [Admin\Lacarte\ItemController::class, 'switch'])->name('item.switch');

        Route::get('item/create', [Admin\Lacarte\ItemController::class, 'create'])->name('item.create');
        Route::post('item/create', [Admin\Lacarte\ItemController::class, 'store'])->name('item.store');
        Route::get('item/edit/{id}', [Admin\Lacarte\ItemController::class, 'edit'])->name('item.edit');
        Route::post('item/edit/{id}', [Admin\Lacarte\ItemController::class, 'update'])->name('item.update');

        Route::post('item/updateMeetingForm/{id}', [Admin\Lacarte\ItemController::class, 'updateMeetingForm'])->name('item.updateMeetingForm');
    });

    Route::prefix('module')->name('module.')->group(function () {
        Route::get('category', [Admin\Module\CategoryController::class, 'index'])->name('category.index');
        Route::post('category', [Admin\Module\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/switch', [Admin\Module\CategoryController::class, 'switch'])->name('category.switch');
        Route::get('category/sort', [Admin\Module\CategoryController::class, 'getSort'])->name('category.sort');
        Route::post('category/sort', [Admin\Module\CategoryController::class, 'updateSort']);

        Route::get('item', [Admin\Module\ItemController::class, 'index'])->name('item.index');
        Route::get('item/switch', [Admin\Module\ItemController::class, 'switch'])->name('item.switch');

        Route::get('item/create', [Admin\Module\ItemController::class, 'create'])->name('item.create');
        Route::post('item/create', [Admin\Module\ItemController::class, 'store'])->name('item.store');
        Route::get('item/edit/{id}', [Admin\Module\ItemController::class, 'edit'])->name('item.edit');
        Route::post('item/edit/{id}', [Admin\Module\ItemController::class, 'update'])->name('item.update');

        Route::post('item/createPrice/{id}', [Admin\Module\ItemController::class, 'createPrice'])->name('item.createPrice');
        Route::delete('item/deletePrice/{id}', [Admin\Module\ItemController::class, 'deletePrice'])->name('item.deletePrice');
        Route::post('item/updateMeetingForm/{id}', [Admin\Module\ItemController::class, 'updateMeetingForm'])->name('item.updateMeetingForm');
    });

    Route::prefix('package')->name('package.')->group(function () {
        Route::get('item', [Admin\Package\ItemController::class, 'index'])->name('item.index');
        Route::get('item/switch', [Admin\Package\ItemController::class, 'switch'])->name('item.switch');
        Route::get('item/sort', [Admin\Package\ItemController::class, 'getSort'])->name('item.sort');
        Route::post('item/sort', [Admin\Package\ItemController::class, 'updateSort']);

        Route::get('item/create', [Admin\Package\ItemController::class, 'create'])->name('item.create');
        Route::post('item/create', [Admin\Package\ItemController::class, 'store'])->name('item.store');
        Route::get('item/edit/{id}', [Admin\Package\ItemController::class, 'edit'])->name('item.edit');
        Route::post('item/edit/{id}', [Admin\Package\ItemController::class, 'update'])->name('item.update');

        Route::post('item/updateModule/{id}', [Admin\Package\ItemController::class, 'updateModule'])->name('item.updateModule');
        Route::post('item/createPrice/{id}', [Admin\Package\ItemController::class, 'createPrice'])->name('item.createPrice');
        Route::delete('item/deletePrice/{id}', [Admin\Package\ItemController::class, 'deletePrice'])->name('item.deletePrice');
        Route::post('item/updateMeetingForm/{id}', [Admin\Package\ItemController::class, 'updateMeetingForm'])->name('item.updateMeetingForm');
    });

    Route::prefix('readymade')->name('readymade.')->group(function () {
        Route::get('category', [Admin\ReadyMade\CategoryController::class, 'index'])->name('category.index');
        Route::post('category', [Admin\ReadyMade\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/switch', [Admin\ReadyMade\CategoryController::class, 'switch'])->name('category.switch');
        Route::get('category/sort', [Admin\ReadyMade\CategoryController::class, 'getSort'])->name('category.sort');
        Route::post('category/sort', [Admin\ReadyMade\CategoryController::class, 'updateSort']);

        Route::get('item', [Admin\ReadyMade\ItemController::class, 'index'])->name('item.index');
        Route::get('item/switch', [Admin\ReadyMade\ItemController::class, 'switch'])->name('item.switch');

        Route::get('item/create', [Admin\ReadyMade\ItemController::class, 'create'])->name('item.create');
        Route::post('item/create', [Admin\ReadyMade\ItemController::class, 'store'])->name('item.store');
        Route::get('item/edit/{id}', [Admin\ReadyMade\ItemController::class, 'edit'])->name('item.edit');
        Route::post('item/edit/{id}', [Admin\ReadyMade\ItemController::class, 'update'])->name('item.update');

        Route::post('item/updateModule/{id}', [Admin\ReadyMade\ItemController::class, 'updateModule'])->name('item.updateModule');
        Route::post('item/createPrice/{id}', [Admin\ReadyMade\ItemController::class, 'createPrice'])->name('item.createPrice');
        Route::delete('item/deletePrice/{id}', [Admin\ReadyMade\ItemController::class, 'deletePrice'])->name('item.deletePrice');
        Route::post('item/updateMeetingForm/{id}', [Admin\ReadyMade\ItemController::class, 'updateMeetingForm'])->name('item.updateMeetingForm');
    });

    Route::prefix('review')->name('review.')->group(function () {
        Route::get('/', [Admin\ReviewController::class, 'index'])->name('index');
        Route::get('/show/{id}', [Admin\ReviewController::class, 'show'])->name('show');
        Route::get('/edit', [Admin\ReviewController::class, 'edit'])->name('edit');
        Route::post('/edit', [Admin\ReviewController::class, 'update'])->name('update');
        Route::get('/switch', [Admin\ReviewController::class, 'switch'])->name('switch');
    });

    Route::prefix('slider')->name('slider.')->group(function () {
        Route::get('/', [Admin\SliderController::class, 'index'])->name('index');
        Route::post('/', [Admin\SliderController::class, 'store'])->name('store');
        Route::get('/product', [Admin\SliderController::class, 'product'])->name('product');
        Route::get('/switch', [Admin\SliderController::class, 'switch'])->name('switch');
        Route::get('/sort', [Admin\SliderController::class, 'getSort'])->name('sort');
        Route::post('/sort', [Admin\SliderController::class, 'updateSort']);
    });

    Route::prefix('testimonial')->name('testimonial.')->group(function () {
        Route::get('/', [Admin\TestimonialController::class, 'index'])->name('index');
        Route::post('/', [Admin\TestimonialController::class, 'store'])->name('store');
        Route::get('/switch', [Admin\TestimonialController::class, 'switch'])->name('switch');
        Route::get('/sort', [Admin\TestimonialController::class, 'getSort'])->name('sort');
        Route::post('/sort', [Admin\TestimonialController::class, 'updateSort']);
    });

    Route::prefix('quick-tours')->name('quick-tours.')->group(function () {
        Route::get('/', [Admin\QuickTourController::class, 'index'])->name('index');
        Route::post('/', [Admin\QuickTourController::class, 'store'])->name('store');
        Route::get('/switch', [Admin\QuickTourController::class, 'switch'])->name('switch');
        Route::get('/sort', [Admin\QuickTourController::class, 'getSort'])->name('sort');
        Route::post('/sort', [Admin\QuickTourController::class, 'updateSort']);
        Route::get('/get-target-ids', [Admin\QuickTourController::class, 'getTargetIds'])->name('get-target-ids');
    });

    Route::prefix('coupon')->name('coupon.')->group(function () {
        Route::get('/', [Admin\CouponController::class, 'index'])->name('index');
        Route::post('/', [Admin\CouponController::class, 'store'])->name('store');
        Route::get('/product', [Admin\CouponController::class, 'product'])->name('product');
        Route::get('/edit', [Admin\CouponController::class, 'edit'])->name('edit');
        Route::get('/switch', [Admin\CouponController::class, 'switch'])->name('switch');
    });

    Route::prefix('legalPage')->name('legalPage.')->group(function () {
        Route::get('/', [Admin\LegalPageController::class, 'index'])->name('index');
        Route::get('/edit/{slug}', [Admin\LegalPageController::class, 'edit'])->name('edit');
        Route::post('/edit/{slug}', [Admin\LegalPageController::class, 'update'])->name('update');
    });

    Route::prefix('teamManage')->name('teamManage.')->group(function () {
        Route::get('property', [Admin\TeamManage\PropertyController::class, 'index'])->name('property.index');
        Route::post('property', [Admin\TeamManage\PropertyController::class, 'store'])->name('property.store');
        Route::get('property/switch', [Admin\TeamManage\PropertyController::class, 'switch'])->name('property.switch');
        Route::get('property/sort', [Admin\TeamManage\PropertyController::class, 'getSort'])->name('property.sort');
        Route::post('property/sort', [Admin\TeamManage\PropertyController::class, 'updateSort']);

        Route::get('team', [Admin\TeamManage\TeamController::class, 'index'])->name('team.index');
        Route::get('team/create/{slug?}', [Admin\TeamManage\TeamController::class, 'create'])->name('team.create');
        Route::get('team/selectUser', [Admin\TeamManage\TeamController::class, 'selectUser'])->name('team.selectUser');
        Route::post('team/create/{slug?}', [Admin\TeamManage\TeamController::class, 'store'])->name('team.store');
        Route::get('team/switch', [Admin\TeamManage\TeamController::class, 'switch'])->name('team.switch');
        Route::get('team/edit/{id}', [Admin\TeamManage\TeamController::class, 'edit'])->name('team.edit');
        Route::post('team/edit/{id}', [Admin\TeamManage\TeamController::class, 'update'])->name('team.update');
        Route::get('team/{slug}/subteams', [Admin\TeamManage\TeamController::class, 'subteam'])->name('team.subteam');
    });

    Route::prefix('livechat')->name('livechat.')->group(function () {
        Route::get('setting', [Admin\LiveChat\SettingController::class, 'index'])->name('setting.index');
        Route::post('setting', [Admin\LiveChat\SettingController::class, 'store'])->name('setting.store');

        Route::get('service', [Admin\LiveChat\ServiceController::class, 'index'])->name('service.index');
        Route::post('service', [Admin\LiveChat\ServiceController::class, 'store'])->name('service.store');
        Route::get('service/switch', [Admin\LiveChat\ServiceController::class, 'switch'])->name('service.switch');
        Route::get('service/sort', [Admin\LiveChat\ServiceController::class, 'getSort'])->name('service.sort');
        Route::post('service/sort', [Admin\LiveChat\ServiceController::class, 'updateSort']);

        Route::get('chat', [Admin\LiveChat\ChatController::class, 'index'])->name('chat.index');
        Route::get('chatbox', [Admin\LiveChat\ChatController::class, 'chatbox'])->name('chatbox.index');
        Route::get('chatbox/getContent', [Admin\LiveChat\ChatController::class, 'getContent'])->name('chatbox.getContent');
        Route::get('chatbox/updateUnreads', [Admin\LiveChat\ChatController::class, 'updateUnreads'])->name('chatbox.updateUnreads');
        Route::get('chatbox/readMessage', [Admin\LiveChat\ChatController::class, 'readMessage'])->name('chatbox.readMessage');
        Route::get('chatbox/endGuestChat', [Admin\LiveChat\ChatController::class, 'endGuestChat'])->name('chatbox.endGuestChat');
        Route::get('chatbox/transcriptChat', [Admin\LiveChat\ChatController::class, 'transcriptChat'])->name('chatbox.transcriptChat');
        Route::get('chatbox/getDetail', [Admin\LiveChat\ChatController::class, 'getDetail'])->name('chatbox.getDetail');
        Route::post('chatbox/sendMessage', [Admin\LiveChat\ChatController::class, 'sendMessage'])->name('chatbox.sendMessage');
    });

    //HelpCenter FAQ Category routes
    Route::prefix('faq')->name('faq.')->group(function () {
        Route::get('category', [Admin\Faq\CategoryController::class, 'index'])->name('category.index');
        Route::post('category', [Admin\Faq\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/switch', [Admin\Faq\CategoryController::class, 'switch'])->name('category.switch');
        Route::get('category/sort', [Admin\Faq\CategoryController::class, 'getSort'])->name('category.sort');
        Route::post('category/sort', [Admin\Faq\CategoryController::class, 'updateSort']);

        Route::get('item', [Admin\Faq\ItemController::class, 'index'])->name('item.index');
        Route::get('item/create', [Admin\Faq\ItemController::class, 'create'])->name('item.create');
        Route::post('item/create', [Admin\Faq\ItemController::class, 'store'])->name('item.store');
        Route::get('item/edit/{id}', [Admin\Faq\ItemController::class, 'edit'])->name('item.edit');
        Route::post('item/edit/{id}', [Admin\Faq\ItemController::class, 'update'])->name('item.update');
        Route::get('item/switch', [Admin\Faq\ItemController::class, 'switch'])->name('item.switch');
    });

    Route::prefix('theme')->name('theme.')->group(function () {
        // admin.theme.category
        Route::prefix('category')->name('category.')->group(function () {
            Route::get('/', [Admin\Theme\ThemeCategoryController::class, 'index'])->name('index');
            Route::post('/', [Admin\Theme\ThemeCategoryController::class, 'store'])->name('store');
            Route::get('/switch', [Admin\Theme\ThemeCategoryController::class, 'switch'])->name('switch');
            Route::get('/sort', [Admin\Theme\ThemeCategoryController::class, 'getSort'])->name('get-sort');
            Route::post('/sort', [Admin\Theme\ThemeCategoryController::class, 'updateSort'])->name('update-sort');
        });

        // admin.theme.item
        Route::prefix('item')->name('item.')->group(function () {
            Route::get('/', [Admin\Theme\ThemeItemController::class, 'index'])->name('index');
            Route::post('/', [Admin\Theme\ThemeItemController::class, 'store'])->name('store');
            Route::put('/', [Admin\Theme\ThemeItemController::class, 'update'])->name('update');
            Route::get('switch', [Admin\Theme\ThemeItemController::class, 'switch'])->name('switch');
            Route::post('/delete', [Admin\Theme\ThemeItemController::class, 'delete'])->name('delete');
        });
    });

    Route::prefix('ticket')->name('ticket.')->group(function () {
        Route::get('category', [Admin\Ticket\CategoryController::class, 'index'])->name('category.index');
        Route::post('category', [Admin\Ticket\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/switch', [Admin\Ticket\CategoryController::class, 'switch'])->name('category.switch');
        Route::get('category/sort', [Admin\Ticket\CategoryController::class, 'getSort'])->name('category.sort');
        Route::post('category/sort', [Admin\Ticket\CategoryController::class, 'updateSort']);

        Route::get('item', [Admin\Ticket\ItemController::class, 'index'])->name('item.index');
        Route::get('item/reply/{id}', [Admin\Ticket\ItemController::class, 'edit'])->name('item.edit');
        Route::get('item/show/{id}', [Admin\Ticket\ItemController::class, 'show'])->name('item.show');
        Route::post('item/reply/{id}', [Admin\Ticket\ItemController::class, 'update'])->name('item.update');
        Route::get('item/switch', [Admin\Ticket\ItemController::class, 'switchTicket'])->name('item.switch');
    });

    Route::prefix('appointment')->name('appointment.')->group(function () {
        Route::get('setting', [Admin\Appointment\SettingController::class, 'index'])->name('setting.index');
        Route::post('setting', [Admin\Appointment\SettingController::class, 'store'])->name('setting.store');

        Route::get('category', [Admin\Appointment\CategoryController::class, 'index'])->name('category.index');
        Route::get('category/create', [Admin\Appointment\CategoryController::class, 'create'])->name('category.create');
        Route::post('category/create', [Admin\Appointment\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/edit/{id}', [Admin\Appointment\CategoryController::class, 'edit'])->name('category.edit');
        Route::post('category/edit/{id}', [Admin\Appointment\CategoryController::class, 'update'])->name('category.update');
        Route::get('category/switch', [Admin\Appointment\CategoryController::class, 'switch'])->name('category.switch');
        Route::get('category/sort', [Admin\Appointment\CategoryController::class, 'getSort'])->name('category.sort');
        Route::post('category/sort', [Admin\Appointment\CategoryController::class, 'updateSort']);

        Route::get('unavailable-dates/{type}/{id}', [Admin\Appointment\BlockDateController::class, 'index'])->name('blockDate.index');
        Route::get('unavailable-dates/edit/{type}/{id}', [Admin\Appointment\BlockDateController::class, 'edit'])->name('blockDate.edit');
        Route::post('unavailable-dates/{type}/{id}', [Admin\Appointment\BlockDateController::class, 'store'])->name('blockDate.store');
        Route::post('unavailable-dates/delete/{type}/{id}', [Admin\Appointment\BlockDateController::class, 'delete'])->name('blockDate.delete');

        Route::get('/listing', [Admin\Appointment\ListingController::class, 'index'])->name('listing.index');
        Route::get('/listing/getData', [Admin\Appointment\ListingController::class, 'getData'])->name('listing.getData');
        Route::get('/listing/edit/{id}', [Admin\Appointment\ListingController::class, 'edit'])->name('listing.edit');
        Route::post('/listing/approve/{id}', [Admin\Appointment\ListingController::class, 'update'])->name('listing.update');
        Route::get('/listing/detail/{id}', [Admin\Appointment\ListingController::class, 'detail'])->name('listing.detail');
        Route::get('/listing/switch', [Admin\Appointment\ListingController::class, 'switchListing'])->name('listing.switch');
        Route::get('/listing/allListing', [Admin\Appointment\ListingController::class, 'allListing'])->name('listing.allListing');
    });

    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('seo', [Admin\Setting\SeoController::class, 'index'])->name('seo.index');
        Route::post('seo', [Admin\Setting\SeoController::class, 'store'])->name('seo.store');
        Route::get('seo/generateSitemap', [Admin\Setting\SeoController::class, 'generateSitemap'])->name('seo.generateSitemap')->middleware('throttle:sitemap');
        Route::get('seo/downloadSitemap', [Admin\Setting\SeoController::class, 'downloadSitemap'])->name('seo.downloadSitemap')->middleware('throttle:sitemap');

        Route::get('social', [Admin\Setting\SocialController::class, 'index'])->name('social.index');
        Route::post('social', [Admin\Setting\SocialController::class, 'store'])->name('social.store');

        Route::get('payment', [Admin\Setting\PaymentController::class, 'index'])
            ->name('payment.index')
            ->middleware("password.confirm.custom");
        Route::post('payment', [Admin\Setting\PaymentController::class, 'store'])->name('payment.store');

        Route::get('third-party', [Admin\Setting\ThirdPartyController::class, 'index'])
            ->name('third_party.index')
            ->middleware("password.confirm.custom");

        Route::post('third-party', [Admin\Setting\ThirdPartyController::class, 'store'])
            ->name('third_party.store');

        Route::get('firewall', [Admin\Setting\FirewallController::class, 'index'])->name('firewall.index');
        Route::post('firewall', [Admin\Setting\FirewallController::class, 'store'])->name('firewall.store');
        Route::get('firewall/switch', [Admin\Setting\FirewallController::class, 'switch'])->name('firewall.switch');
    });

    Route::prefix('tutorial')->name('tutorial.')->group(function () {
        Route::get('category', [Admin\Tutorial\CategoryController::class, 'index'])->name('category.index');
        Route::post('category', [Admin\Tutorial\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/switch', [Admin\Tutorial\CategoryController::class, 'switch'])->name('category.switch');
        Route::get('category/sort', [Admin\Tutorial\CategoryController::class, 'getSort'])->name('category.sort');
        Route::post('category/sort', [Admin\Tutorial\CategoryController::class, 'updateSort']);

        Route::get('item', [Admin\Tutorial\TutorialController::class, 'index'])->name('item.index');
        Route::get('item/create', [Admin\Tutorial\TutorialController::class, 'create'])->name('item.create');
        Route::get('item/getCategory', [Admin\Tutorial\TutorialController::class, 'getCategory'])->name('item.getCategory');
        Route::post('item/create', [Admin\Tutorial\TutorialController::class, 'store'])->name('item.store');
        Route::get('item/edit/{id}', [Admin\Tutorial\TutorialController::class, 'edit'])->name('item.edit');
        Route::get('item/detail/{id}', [Admin\Tutorial\TutorialController::class, 'detail'])->name('item.detail');
        Route::post('item/edit/{id}', [Admin\Tutorial\TutorialController::class, 'update'])->name('item.update');
        Route::get('item/switch', [Admin\Tutorial\TutorialController::class, 'switch'])->name('item.switch');
    });

    Route::get('announcement', [Admin\AnnouncementController::class, 'index'])->name('announcement.index');
    Route::post('announcement', [Admin\AnnouncementController::class, 'store'])->name('announcement.store');
    Route::get('announcement/switch', [Admin\AnnouncementController::class, 'switch'])->name('announcement.switch');

    Route::prefix('purchase')->name('purchase.')->group(function () {
        Route::get('order', [Admin\Purchase\OrderController::class, 'index'])->name('order.index');
        Route::get('order/detail/{id}', [Admin\Purchase\OrderController::class, 'detail'])->name('order.detail');

        Route::get('subscription', [Admin\Purchase\SubscriptionController::class, 'index'])->name('subscription.index');
        Route::get('subscription/detail/{id}', [Admin\Purchase\SubscriptionController::class, 'detail'])->name('subscription.detail');
        Route::post('subscription/cancel', [Admin\Purchase\SubscriptionController::class, 'cancel'])->name('subscription.cancel');

        Route::get('transaction', [Admin\Purchase\TransactionController::class, 'index'])->name('transaction.index');
        Route::get('transaction/invoice/{id}', [Admin\Purchase\TransactionController::class, 'invoice'])->name('transaction.invoice');
        Route::get('transaction/invoice/{id}/download', [Admin\Purchase\TransactionController::class, 'invoiceDownload'])->name('transaction.invoiceDownload');

        Route::get('form', [Admin\Purchase\FormController::class, 'index'])->name('form.index');
        Route::get('form/detail/{id}', [Admin\Purchase\FormController::class, 'detail'])->name('form.detail');
        Route::get('form/edit/{id}', [Admin\Purchase\FormController::class, 'edit'])->name('form.edit');
        Route::post('form/edit/{id}', [Admin\Purchase\FormController::class, 'update'])->name('form.update');
        Route::get('form/switch', [Admin\Purchase\FormController::class, 'switchForm'])->name('form.switch');

        Route::get('package', [Admin\Purchase\ProductController::class, 'package'])->name('package.index');
        Route::get('package/detail/{id}', [Admin\Purchase\ProductController::class, 'packageDetail'])->name('package.detail');
        Route::get('readymade', [Admin\Purchase\ProductController::class, 'readymade'])->name('readymade.index');
        Route::get('readymade/detail/{id}', [Admin\Purchase\ProductController::class, 'readymadeDetail'])->name('readymade.detail');
        Route::get('blog', [Admin\Purchase\ProductController::class, 'blog'])->name('blog.index');
        Route::get('blog/detail/{id}', [Admin\Purchase\ProductController::class, 'blogDetail'])->name('blog.detail');
        Route::get('plugin', [Admin\Purchase\ProductController::class, 'plugin'])->name('plugin.index');
        Route::get('plugin/detail/{id}', [Admin\Purchase\ProductController::class, 'pluginDetail'])->name('plugin.detail');
        Route::get('lacarte', [Admin\Purchase\ProductController::class, 'lacarte'])->name('lacarte.index');
        Route::get('lacarte/detail/{id}', [Admin\Purchase\ProductController::class, 'lacarteDetail'])->name('lacarte.detail');
        Route::get('service', [Admin\Purchase\ProductController::class, 'service'])->name('service.index');
        Route::get('service/detail/{id}', [Admin\Purchase\ProductController::class, 'serviceDetail'])->name('service.detail');
        Route::get('module', [Admin\Purchase\ProductController::class, 'module'])->name('module.index');
    });

    Route::prefix('notification')->name('notification.')->group(function () {
        Route::get('category', [Admin\Notification\CategoryController::class, 'index'])->name('category.index');
        Route::post('category', [Admin\Notification\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/switch', [Admin\Notification\CategoryController::class, 'switch'])->name('category.switch');

        Route::get('template', [Admin\Notification\TemplateController::class, 'index'])->name('template.index');
        Route::get('template/create', [Admin\Notification\TemplateController::class, 'create'])->name('template.create');
        Route::post('template/create', [Admin\Notification\TemplateController::class, 'store'])->name('template.store');
        Route::get('template/edit/{id}', [Admin\Notification\TemplateController::class, 'edit'])->name('template.edit');
        Route::get('template/show/{id}', [Admin\Notification\TemplateController::class, 'show'])->name('template.show');
        Route::get('template/switch', [Admin\Notification\TemplateController::class, 'switch'])->name('template.switch');
    });

    Route::prefix('home-sliders')->name('home-sliders.')->group(function () {
        Route::get('/', [Admin\ContentManagement\HomeSliderController::class, 'index'])->name('index');
        Route::post('/', [Admin\ContentManagement\HomeSliderController::class, 'store'])->name('store');
        Route::get('/switch', [Admin\ContentManagement\HomeSliderController::class, 'switch'])->name('switch');
    });

    Route::prefix('boxes')->name('boxes.')->group(function () {
        Route::get('/', [Admin\ContentManagement\BoxesController::class, 'index'])->name('index');
        Route::post('/middle-box', [Admin\ContentManagement\BoxesController::class, 'middleBox'])->name('middleBox');
        Route::post('/', [Admin\ContentManagement\BoxesController::class, 'store'])->name('store');
        Route::get('/switch', [Admin\ContentManagement\BoxesController::class, 'switch'])->name('switch');
    });

    Route::prefix('video')->name('video.')->group(function () {
        Route::get('/', [Admin\ContentManagement\VideoController::class, 'index'])->name('index');
        Route::post('/', [Admin\ContentManagement\VideoController::class, 'update'])->name('update');
    });

    Route::prefix('welcome-video')->name('welcome-video.')->group(function () {
        Route::get('/', [Admin\ContentManagement\WelcomeVideoController::class, 'index'])->name('index');
        Route::post('/', [Admin\ContentManagement\WelcomeVideoController::class, 'update'])->name('update');
    });

    Route::prefix('getting-started')->name('getting-started.')->group(function () {
        Route::get('/', [Admin\ContentManagement\GettingStartedController::class, 'index'])->name('index');
        Route::post('/', [Admin\ContentManagement\GettingStartedController::class, 'update'])->name('update');
    });

    Route::prefix('video')->name('video.')->group(function () {
        Route::get('category', [Admin\Logo\Video\CategoryController::class, 'index'])->name('category.index');
        Route::post('category', [Admin\Logo\Video\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/switch', [Admin\Logo\Video\CategoryController::class, 'switch'])->name('category.switch');
        Route::get('category/sort', [Admin\Logo\Video\CategoryController::class, 'getSort'])->name('category.sort');
        Route::post('category/sort', [Admin\Logo\Video\CategoryController::class, 'updateSort']);

        Route::get('item', [Admin\Logo\Video\ItemController::class, 'index'])->name('item.index');
        Route::get('item/create', [Admin\Logo\Video\ItemController::class, 'create'])->name('item.create');
        Route::get('item/getCategory', [Admin\Logo\Video\ItemController::class, 'getCategory'])->name('item.getCategory');
        Route::post('item/create', [Admin\Logo\Video\ItemController::class, 'store'])->name('item.store');
        Route::get('item/edit/{id}', [Admin\Logo\Video\ItemController::class, 'edit'])->name('item.edit');
        Route::get('item/detail/{id}', [Admin\Logo\Video\ItemController::class, 'detail'])->name('item.detail');
        Route::post('item/edit/{id}', [Admin\Logo\Video\ItemController::class, 'update'])->name('item.update');
        Route::get('item/switch', [Admin\Logo\Video\ItemController::class, 'switch'])->name('item.switch');
    });

    Route::prefix('home')->name('home.')->group(function () {
        Route::get('/how-to-build', [Admin\ContentManagement\HomePageController::class, 'howToBuild'])->name('how-to-build');
        Route::put('/how-to-build', [Admin\ContentManagement\HomePageController::class, 'updateHowToBuild'])->name('update-how-to-build');
    });

    Route::name('graphics.')->prefix('graphics')->group(function () {
        // Category CRUD
        Route::get('/', [Admin\GraphicDesign\GraphicController::class, 'index'])->name('index');
        Route::post('/', [Admin\GraphicDesign\GraphicController::class, 'store'])->name('store');
        Route::get('/sort', [Admin\GraphicDesign\GraphicController::class, 'getSort'])->name('sort');
        Route::delete('{id}', [Admin\GraphicDesign\GraphicController::class, 'delete'])->name('delete');

        Route::get('media-categories-raw', [Admin\GraphicDesign\GraphicController::class, 'getRaw'])->name('category.raw');
        Route::post('category/sort', [Admin\GraphicDesign\GraphicController::class, 'updateSort']);

        // Masks CRUD
        Route::get('masks-view', [Admin\GraphicDesign\GraphicController::class, 'viewMasks'])->name('masks.view');
        Route::post('masks-add', [Admin\GraphicDesign\GraphicController::class, 'addMask'])->name('masks.add');
        Route::get('images-view', [Admin\GraphicDesign\GraphicController::class, 'viewImages'])->name('images.view');
        Route::post('images-add', [Admin\GraphicDesign\GraphicController::class, 'addImage'])->name('images.add');
        Route::get('icons-view', [Admin\GraphicDesign\GraphicController::class, 'viewIcons'])->name('icons.view');
        Route::post('icons-add', [Admin\GraphicDesign\GraphicController::class, 'addIcon'])->name('icons.add');
        Route::delete('media/delete', [Admin\GraphicDesign\GraphicController::class, 'deleteMedia'])->name('media.delete');

        // Front Settings CRUD
        Route::get('front', [Admin\GraphicDesign\FrontController::class, 'index'])->name('front.index');
        Route::post('front/{slug}', [Admin\GraphicDesign\FrontController::class, 'store'])->name('front.store');

        // Design Category CRUD
        Route::resource('category', Admin\GraphicDesign\DesignCategoryController::class);

        // Design CRUD
        Route::get('user-design', [Admin\GraphicDesign\UserDesignController::class, 'index'])->name('design.user-index');
        Route::get('design', [Admin\GraphicDesign\DesignController::class, 'index'])->name('design.index');
        Route::get('user/{id}/designs', [Admin\GraphicDesign\DesignController::class, 'user'])->name('user.designs');
        Route::get('design/create', [Admin\GraphicDesign\DesignController::class, 'create'])->name('design.create');
        Route::post('design', [Admin\GraphicDesign\DesignController::class, 'store'])->name('design.store');
        Route::get('design/edit/{id}', [Admin\GraphicDesign\DesignController::class, 'edit'])->name('design.edit');
        Route::post('design/update/{id}', [Admin\GraphicDesign\DesignController::class, 'update'])->name('design.update');
        Route::put('design/switch/{design}', [Admin\GraphicDesign\DesignController::class, 'switch'])->name('design.switch');
        Route::delete('design/delete/{id}', [Admin\GraphicDesign\DesignController::class, 'delete'])->name('design.delete');
        Route::get('design/download/{hash}', [Admin\GraphicDesign\DesignController::class, 'download'])->name('design.download');

        // Fonts
        Route::get('font', [Admin\GraphicDesign\FontController::class, 'index'])->name('font.index');
        Route::post('font', [Admin\GraphicDesign\FontController::class, 'store'])->name('font.store');
        Route::get('font/refresh', [Admin\GraphicDesign\FontController::class, 'refresh'])->name('font.refresh');
        Route::delete('font/delete/{id}', [Admin\GraphicDesign\FontController::class, 'delete'])->name('font.delete');
    });

    Route::prefix('palette-idea')->name('palette-idea.')->group(function () {
        Route::get('category', [Admin\Logo\Portfolio\CategoryController::class, 'index'])->name('category.index');
        Route::post('category', [Admin\Logo\Portfolio\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/switch', [Admin\Logo\Portfolio\CategoryController::class, 'switch'])->name('category.switch');
        Route::get('category/sort', [Admin\Logo\Portfolio\CategoryController::class, 'getSort'])->name('category.sort');
        Route::post('category/sort', [Admin\Logo\Portfolio\CategoryController::class, 'updateSort']);

        Route::get('item', [Admin\Logo\Portfolio\ItemController::class, 'index'])->name('item.index');
        Route::get('item/create', [Admin\Logo\Portfolio\ItemController::class, 'create'])->name('item.create');
        Route::post('item/create', [Admin\Logo\Portfolio\ItemController::class, 'store'])->name('item.store');
        Route::get('item/edit/{id}', [Admin\Logo\Portfolio\ItemController::class, 'edit'])->name('item.edit');
        Route::post('item/edit/{id}', [Admin\Logo\Portfolio\ItemController::class, 'update'])->name('item.update');
        Route::get('item/switch', [Admin\Logo\Portfolio\ItemController::class, 'switch'])->name('item.switch');
    });

    // admin.palettes
    Route::prefix('palettes')->group(function () {
        Route::get('{type}/categories', [Admin\PaletteController::class, 'categoriesView'])->name('palettes.categories.view');
        Route::get('{type}/category/switch', [Admin\PaletteController::class, 'categorySwitch'])->name('palettes.category.switch');
        Route::post('{type}/category', [Admin\PaletteController::class, 'categoryStore'])->name('palettes.category.store');
        Route::get('{type}/categories/datatable', [Admin\PaletteController::class, 'categoriesDataTable'])->name('palettes.categories.datatable');
        Route::get('{type}/categories/sort/view', [Admin\PaletteController::class, 'sortView'])->name('palettes.categories.sort.view');
        Route::post('{type}/categories/sort', [Admin\PaletteController::class, 'sortCategories'])->name('palettes.categories.sort');

        Route::get('{type}/palettes', [Admin\PaletteController::class, 'palettesView'])->name('palettes.view');
        Route::get('{type}/palettes/data', [Admin\PaletteController::class, 'palettesData'])->name('palettes.data');
        Route::post('{type}/palette', [Admin\PaletteController::class, 'paletteStore'])->name('palettes.store');
        Route::put('{type}/palette', [Admin\PaletteController::class, 'paletteUpdate'])->name('palettes.update');
        Route::post('{type}/palettes/sort', [Admin\PaletteController::class, 'palettesSort'])->name('palettes.sort');
        Route::delete('{type}/palette', [Admin\PaletteController::class, 'paletteDelete'])->name('palettes.delete');
    });

    // admin.newsletter
    Route::prefix('newsletter')->name('newsletter.')->group(function () {
        Route::get('items', [Admin\Newsletter\ItemController::class, 'index'])->name('item.index');
        Route::get('/create', [Admin\Newsletter\ItemController::class, 'create'])->name('create');
        Route::post('item/store', [Admin\Newsletter\ItemController::class, 'store'])->name('item.store');
        Route::get('item/design/{slug}', [Admin\Newsletter\ItemController::class, 'design'])->name('item.design');
        Route::get('item/edit/{slug}', [Admin\Newsletter\ItemController::class, 'edit'])->name('item.edit');
        Route::post('item/update/{slug}/{type}', [Admin\Newsletter\ItemController::class, 'update'])->name('item.update');
        Route::get('item/preview/{slug}', [Admin\Newsletter\ItemController::class, 'preview'])->name('item.preview');
        Route::get('/item/review/{slug}', [Admin\Newsletter\ItemController::class, 'review'])->name('item.review');
        Route::post('/item/test/{slug}', [Admin\Newsletter\ItemController::class, 'test'])->name('item.test');
        Route::post('item/send/{slug}', [Admin\Newsletter\ItemController::class, 'send'])->name('item.send');
        Route::delete('item/delete/{slug}', [Admin\Newsletter\ItemController::class, 'delete'])->name('item.delete');

        Route::get('templates', [Admin\Newsletter\TemplateController::class, 'index'])->name('template.index');
        Route::get('template/create', [Admin\Newsletter\TemplateController::class, 'create'])->name('template.create');
        Route::post('template/store', [Admin\Newsletter\TemplateController::class, 'store'])->name('template.store');
        Route::get('template/edit/{slug}', [Admin\Newsletter\TemplateController::class, 'edit'])->name('template.edit');
        Route::post('template/update/{slug}', [Admin\Newsletter\TemplateController::class, 'update'])->name('template.update');
        Route::post('template/rename/{slug}', [Admin\Newsletter\TemplateController::class, 'rename'])->name('template.rename');
        Route::get('template/preview/{slug}', [Admin\Newsletter\TemplateController::class, 'preview'])->name('template.preview');
        Route::delete('template/delete/{slug}', [Admin\Newsletter\TemplateController::class, 'delete'])->name('template.delete');
    });

    Route::prefix('newsletterAds')->name('newsletterAds.')->group(function () {
        Route::get('type', [Admin\NewsletterAds\TypeController::class, 'index'])->name('type.index');
        Route::post('type', [Admin\NewsletterAds\TypeController::class, 'store'])->name('type.store');
        Route::get('type/switch', [Admin\NewsletterAds\TypeController::class, 'switch'])->name('type.switch');

        Route::get('position', [Admin\NewsletterAds\PositionController::class, 'index'])->name('position.index');
        Route::get('position/create', [Admin\NewsletterAds\PositionController::class, 'create'])->name('position.create');
        Route::post('position/store', [Admin\NewsletterAds\PositionController::class, 'store'])->name('position.store');
        Route::get('position/switch', [Admin\NewsletterAds\PositionController::class, 'switch'])->name('position.switch');
        Route::get('position/edit/{id}', [Admin\NewsletterAds\PositionController::class, 'edit'])->name('position.edit');
        Route::post('position/update/{id}', [Admin\NewsletterAds\PositionController::class, 'update'])->name('position.update');
        Route::post('position/createPrice/{id}', [Admin\NewsletterAds\PositionController::class, 'createPrice'])->name('position.createPrice');
        Route::delete('position/deletePrice/{id}', [Admin\NewsletterAds\PositionController::class, 'deletePrice'])->name('position.deletePrice');
        Route::post('position/updateListing/{id}', [Admin\NewsletterAds\PositionController::class, 'updateListing'])->name('position.updateListing');

        Route::get('listing', [Admin\NewsletterAds\ListingController::class, 'index'])->name('listing.index');
        Route::get('listing/select', [Admin\NewsletterAds\ListingController::class, 'select'])->name('listing.select');
        Route::get('listing/create/{slug}', [Admin\NewsletterAds\ListingController::class, 'create'])->name('listing.create');
        Route::post('listing/store/{slug}', [Admin\NewsletterAds\ListingController::class, 'store'])->name('listing.store');
        Route::get('listing/show/{id}', [Admin\NewsletterAds\ListingController::class, 'show'])->name('listing.show');
        Route::get('listing/edit/{id}', [Admin\NewsletterAds\ListingController::class, 'edit'])->name('listing.edit');
        Route::post('listing/update/{id}', [Admin\NewsletterAds\ListingController::class, 'update'])->name('listing.update');
        Route::get('listing/tracking/{id}', [Admin\NewsletterAds\ListingController::class, 'tracking'])->name('listing.tracking');
        Route::get('listing/switch', [Admin\NewsletterAds\ListingController::class, 'switchListing'])->name('listing.switch');
    });
});
