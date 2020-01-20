<?php

use App\Http\Controllers as Root;
use App\Http\Controllers\Auth;
use App\Http\Controllers\CaddyResponseController;
use App\Http\Controllers\Front as Front;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\User\Domain\DomainController;
use Illuminate\Support\Facades\Route; 
use Spatie\Honeypot\ProtectAgainstSpam;

Route::domain('accounts.' . config('app.domain'))->group(function () {
    Illuminate\Support\Facades\Auth::routes([
        'login' => true,
        'logout' => true,
        'register' => false,
        'reset' => true,
        'confirm' => true,
        'verify' => true,
    ]);

    Route::get('register', [Auth\CustomController::class, 'email'])
        ->name('register');
    Route::get('resend-email', [Auth\CustomController::class, 'resendVerification'])
        ->name('resend-email');
    Route::post('register/email', [Auth\CustomController::class, 'emailSubmit'])
        ->name('register.email');

    Route::get('register/password', [Auth\CustomController::class, 'password'])->name('register.password');
    Route::post('register/password', [Auth\CustomController::class, 'passwordSubmit']);

    Route::get('/{any}', function () {
        return redirect('/login');
    })->where('any', '.*');
});

Route::get('ssoLogin', [Root\Auth\CustomController::class, 'ssoLogin'])->name('ssoLogin');
Route::get('ssoRegister', [Root\Auth\CustomController::class, 'ssoRegister'])->name('ssoRegister');
Route::post('ssoLogout', [Root\Auth\CustomController::class, 'ssoLogout'])->name('ssoLogout');

Route::get('/set-tester-mode', function () {
    session()->put('mode', 'tester');
    return redirect()->route('user.dashboard');
})->name('set.tester.mode');
Route::get('/set-user-mode', function () {
    if (session()->has('mode')) {
        session()->forget('mode');
    }
    return redirect()->route('user.dashboard');
})->name('set.user.mode');

Route::redirect('/login', "https://accounts.bizinabox.com/login");
Route::redirect('/register', "https://accounts.bizinabox.com/register");

Route::get('/', [Front\HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [Front\HomeController::class, 'sitemap'])->name('sitemap.get');
Route::get('/videos', [Root\HomeController::class, 'videos'])->name('videos');
Route::get('/hacker/{slug}', [Front\HomeController::class, 'hacker'])->name('hacker');

Route::post('/subscribe', [Front\HomeController::class, 'subscribe'])->name('subscribe')->middleware(ProtectAgainstSpam::class);
Route::get('/subscribe/{token}', [Front\HomeController::class, 'subscribeConfirm'])->name('subscribe.confirm');
Route::get('/unsubscribe', [Front\HomeController::class, 'unsubscribe'])->name('unsubscribe');
Route::post('/unsubscribe', [Front\HomeController::class, 'unsubscribeConfirm'])->name('unsubscribe.confirm')->middleware(ProtectAgainstSpam::class);
Route::get('/unsubscribe/newsletter', [Front\HomeController::class, 'unsubscribeNewsletter'])->name('unsubscribe.newsletter');
Route::get('/getImage/{path}', [Front\HomeController::class, 'getImage'])->name('getImage')->where('path', '(.*)');
Route::get('/about', [Front\HomeController::class, 'about'])->name('legal');
Route::get('/legal/{slug}', [Front\HomeController::class, 'legal'])->name('legal');

Route::get('/faq', [Front\FaqController::class, 'index'])->name('faq.index');
Route::get('/contact', [Front\FaqController::class, 'contact'])->name('contact');
Route::post('/contact/sendmail', [Front\FaqController::class, 'contactSendMail'])->name('contact.sendmail');
Route::get('/mail/{id}', [Front\HomeController::class, 'mail'])->name('mail.view');

Route::get('/templates', [Front\TemplateController::class, 'index'])->name('template.index');
Route::get('/templates/view/{slug}', [Front\TemplateController::class, 'view'])->name('template.view');
Route::get('/templates/get', [Front\TemplateController::class, 'get'])->name('template.get');
Route::get('/template/preview/{slug}', [Front\TemplateController::class, 'preview'])->name('template.preview');
Route::get('/templates/{slug}', [Front\TemplateController::class, 'detail'])->name('template.detail');
Route::get('/start/{slug}', [Front\TemplateController::class, 'start'])->name('template.start');
Route::get('/template-preview/{id}/{url?}', [Front\TemplateController::class, 'templatePreview'])->name('template.builder.preview');

Route::get('/services', [Front\ServiceController::class, 'index'])->name('service.index');
Route::get('/services/{slug}', [Front\ServiceController::class, 'detail'])->name('service.detail');
Route::get('/services/{id}/addtocart', [Front\ServiceController::class, 'addtoCart'])->name('service.addtoCart');

Route::get('/plugins', [Front\PluginController::class, 'index'])->name('plugin.index');
Route::get('/plugins/{slug}', [Front\PluginController::class, 'detail'])->name('plugin.detail');
Route::get('/plugins/{id}/addtocart', [Front\PluginController::class, 'addtoCart'])->name('plugin.addtoCart');

Route::get('/lacarte', [Front\LacarteController::class, 'index'])->name('lacarte.index');
Route::get('/lacarte/{slug}', [Front\LacarteController::class, 'detail'])->name('lacarte.detail');
Route::get('/lacarte/{id}/addtocart', [Front\LacarteController::class, 'addtoCart'])->name('lacarte.addtoCart');

Route::get('/modules', [Front\ModuleController::class, 'index'])->name('module.index');
Route::get('/modules/deltocart', [Front\ModuleController::class, 'deltocart'])->name('module.index');
Route::get('/modules/selected-modules', [Front\ModuleController::class, 'selectedModules'])->name('selectedModules');
Route::get('/modules/available-modules', [Front\ModuleController::class, 'availableModules'])->name('availableModules');
Route::get('/modules/module-detail', [Front\ModuleController::class, 'moduleDetail'])->name('moduleDetail');
Route::get('/modules/{slug}', [Front\ModuleController::class, 'detail'])->name('module.detail');
Route::get('/modules/{id}/addtocart', [Front\ModuleController::class, 'addtoCart'])->name('module.addtoCart');

Route::get('/package', [Front\PackageController::class, 'index'])->name('package.index');
Route::get('/package/{slug}', [Front\PackageController::class, 'detail'])->name('package.detail');
Route::get('/package/{id}/addtocart', [Front\PackageController::class, 'addtoCart'])->name('package.addtoCart');

Route::get('/readymade', [Front\ReadyMadeController::class, 'index'])->name('readymade.index');
Route::get('/readymade/{slug}', [Front\ReadyMadeController::class, 'detail'])->name('readymade.detail');
Route::get('/readymade/{id}/addtocart', [Front\ReadyMadeController::class, 'addtoCart'])->name('readymade.addtoCart');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [Front\CartController::class, 'index'])->name('index');
    Route::get('/remove', [Front\CartController::class, 'remove'])->name('remove');
    Route::get('/coupon', [Front\CartController::class, 'coupon'])->name('coupon');
    Route::get('/empty', [Front\CartController::class, 'empty'])->name('empty');
    Route::post('/update', [Front\CartController::class, 'update'])->name('update');
    Route::get('/checkout', [Front\PaymentController::class, 'checkout'])->name('checkout');
    Route::get('/login', [Front\PaymentController::class, 'login'])->name('login');
    Route::post('/checkEmail', [Front\CartController::class, 'checkEmail'])->name('checkEmail')
        ->middleware(ProtectAgainstSpam::class);
    Route::post('/paypal/getUrl', [Front\PaymentController::class, 'paypalGetUrl'])->name('paypal.getUrl')
        ->middleware(ProtectAgainstSpam::class);
    Route::get('/paypal/execute', [Front\PaymentController::class, 'paypalExecute'])->name('paypal.execute');
    Route::post('/stripe/execute', [Front\PaymentController::class, 'stripeExecute'])->name('stripe.execute')
        ->middleware(ProtectAgainstSpam::class);
});

Route::get('/slider/{id}', [Front\SliderController::class, 'detail'])->name('slider.detail');
Route::get('/slider/addtocart/{id}', [Front\SliderController::class, 'addtocart'])->name('slider.addtocart');

Route::get('/livechat', [Front\LiveChatController::class, 'index'])->name('livechat.index');
Route::get('/livechat/end', [Front\LiveChatController::class, 'end'])->name('livechat.end');
Route::post('/livechat/getSession', [Front\LiveChatController::class, 'getSession'])->name('livechat.getSession');
Route::post('/livechat/createSession', [Front\LiveChatController::class, 'createSession'])->name('livechat.createSession')
    ->middleware(ProtectAgainstSpam::class);
Route::post('/livechat/sendMessage', [Front\LiveChatController::class, 'sendMessage'])->name('livechat.sendMessage');
Route::post('/livechat/getService', [Front\LiveChatController::class, 'getService'])->name('livechat.getService');
Route::post('/livechat/sessionClear', [Front\LiveChatController::class, 'sessionClear'])->name('livechat.sessionClear');

Route::post('/livechat/submitService', [Front\LiveChatController::class, 'submitService'])->name('livechat.submitService')
    ->middleware(ProtectAgainstSpam::class);

Route::prefix('blogAds')->name('blogAds.')->group(function () {
    Route::get('/', [Front\BlogAdsController::class, 'index'])->name('index');
    Route::post('/addToCart/{id}', [Front\BlogAdsController::class, 'addToCart'])->name('addtocart');
    Route::get('/spot/{slug}', [Front\BlogAdsController::class, 'spot'])->name('spot');
    Route::post('/impClick', [Front\BlogAdsController::class, 'impClick'])->name('impClick');
    Route::post('/getData', [Front\BlogAdsController::class, 'getData'])->name('getData');
});

Route::prefix('newsletterAds')->name('newsletterAds.')->group(function () {
    Route::get('/', [Front\NewsletterAdsController::class, 'index'])->name('index');
    Route::post('/addToCart/{id}', [Front\NewsletterAdsController::class, 'addToCart'])->name('addtocart');
    Route::get('/position/{slug}', [Front\NewsletterAdsController::class, 'position'])->name('position');
    Route::post('/impClick', [Front\NewsletterAdsController::class, 'impClick'])->name('impClick');
    Route::post('/getData', [Front\NewsletterAdsController::class, 'getData'])->name('getData');
});

Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('', [Front\BlogController::class, 'index'])->name('index');
    Route::get('/packages', [Front\BlogController::class, 'package'])->name('package');
    Route::get('/packages/{slug}', [Front\BlogController::class, 'packageDetail'])->name('package.detail');
    Route::get('/packages/{id}/addToCart', [Front\BlogController::class, 'addToCart'])->name('addtocart');
    Route::get('/ajaxPage', [Front\BlogController::class, 'ajaxPage'])->name('ajaxPage');
    Route::get('/ajaxCategory/{id}', [Front\BlogController::class, 'ajaxCategory'])->name('ajaxCategory');
    Route::get('/ajaxTag/{id}', [Front\BlogController::class, 'ajaxTag'])->name('ajaxTag');
    Route::get('/ajaxAuthor/{username}', [Front\BlogController::class, 'ajaxAuthor'])->name('ajaxAuthor');
    Route::get('/ajaxComment/{slug}', [Front\BlogController::class, 'ajaxComment'])->name('ajaxComment');
    Route::get('/detail/{slug}', [Front\BlogController::class, 'detail'])->name('detail');
    Route::get('/tag/{slug}', [Front\BlogController::class, 'tag'])->name('tag');
    Route::get('/category/{slug}', [Front\BlogController::class, 'category'])->name('category');
    Route::get('/all-posts', [Front\BlogController::class, 'allPosts'])->name('allPosts');
    Route::get('/search', [Front\BlogController::class, 'search'])->name('search');
    Route::get('/getCommentForm/{id}', [Front\BlogController::class, 'getCommentForm'])->name('getCommentForm');
    Route::get('/author/{username}', [Front\BlogController::class, 'author'])->name('author');
    Route::get('/favoriteComment/add', [Front\BlogController::class, 'favoriteComment'])->name('favoriteComment');
});

Route::get('/portfolio', [Front\PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio/categories', [Front\PortfolioController::class, 'categories'])->name('portfolio.categories');
Route::get('/portfolio/category/{slug}', [Front\PortfolioController::class, 'category'])->name('portfolio.category');
Route::get('/portfolio/{slug}', [Front\PortfolioController::class, 'detail'])->name('portfolio.detail');

Route::get('/review', [Front\ReviewController::class, 'index'])->name('review.index');
Route::middleware(ProtectAgainstSpam::class)->group(function () {
    Route::post('/review', [Front\ReviewController::class, 'store'])->name('review.store');
    Route::post('/blog/postComment/{id}', [Front\BlogController::class, 'postComment'])->name('blog.postComment');
});

Route::name('directory.')->prefix('directory')->group(function () {
    Route::get('/', [Front\DirectoryController::class, 'index'])->name('index');
    Route::get('/categories', [Front\DirectoryController::class, 'categories'])->name('categories');
    Route::get('/category/{slug}', [Front\DirectoryController::class, 'category'])->name('category');
    Route::get('/category/{category}/{subCategory}', [Front\DirectoryController::class, 'subCategory'])->name('subCategory');
    Route::get('/tag/{slug}', [Front\DirectoryController::class, 'tag'])->name('tag');
    Route::get('/detail/{slug}', [Front\DirectoryController::class, 'detail'])->name('detail');
    Route::get('/packages', [Front\DirectoryController::class, 'package'])->name('package');
    Route::get('/packages/{slug}', [Front\DirectoryController::class, 'packageDetail'])->name('package.detail');
    Route::get('/packages/{id}/addtocart', [Front\DirectoryController::class, 'addtocart'])->name('package.addtocart');
});

Route::get('/caddy/allowed-domain', [CaddyResponseController::class, 'index'])
    ->name('caddy.response');
// ->middleware('fw-block-blacklisted');

Route::get('auth/{provider}', [Auth\SocialController::class, 'redirectToProvider'])->name('social.login');
Route::get('auth/{provider}/callback', [Auth\SocialController::class, 'handleProviderCallback'])->name('social.redirect');

Route::get('/home', [HomeController::class, 'index'])->name('dashboard');
Route::get('/check-file-existence', [HomeController::class, 'checkFileExistence'])->name('check-file-existence');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/{role}/profile', [HomeController::class, 'profile'])->name('profile');
    Route::post('/account/profileUpdate', [HomeController::class, 'profileUpdate'])->name('account.profile.update');
    Route::post('/account/passwordUpdate', [HomeController::class, 'passwordUpdate'])->name('account.password.update');
    Route::get('/account/setting', [HomeController::class, 'setting'])->name('account.setting');
    Route::post('/account/setting/update', [HomeController::class, 'settingUpdate'])->name('account.setting.update');
});

Route::middleware('auth', 'verified', 'passwordCheck')->group(function () {
    Route::get('/{role}/subscribed', [HomeController::class, 'subscribed'])->name('subscribed');
    Route::post('/{role}/subscribed', [HomeController::class, 'subscribedUpdate'])->name('subscribed.update')->middleware(ProtectAgainstSpam::class);
    Route::get('/{role}/subscribed/switch', [HomeController::class, 'subscribedSwitch'])->name('subscribed.switch');
    Route::get('/{role}/notifications', [HomeController::class, 'notifications'])->name('notification');
    Route::get('/{role}/notifications/{id}/detail', [HomeController::class, 'notificationDetail'])->name('notification.detail');
    Route::get('/{role}/notifications/read-all/{status?}', [HomeController::class, 'readAllNotifications'])->name('notification.read-all');
    Route::get('/{role}/notifications/switch', [HomeController::class, 'notificationSwitch'])->name('notification.switch');
    Route::get('/{role}/notifications/check', [HomeController::class, 'notificationCheck'])->name('notifications.check');

    Route::post('/uploadImage/{folder?}', [HomeController::class, 'uploadImage'])->name('uploadImage');
    Route::post('/uploadImages/{id}', [HomeController::class, 'uploadImages'])->name('uploadImages');
});

// Fonts routing
Route::get('fonts/get', [Root\FontController::class, 'getFonts'])->name('fonts.get');

Route::prefix('graphics')->name('graphics.')->group(function () {
    Route::get('category', [Front\GraphicDesignsController::class, 'index'])->name('index');
    Route::get('category/{slug}', [Front\GraphicDesignsController::class, 'viewCategory'])->name('category');
    Route::get('category/{slug}/design-categories', [Front\GraphicDesignsController::class, 'viewDesignCategories'])->name('design-categories');
    Route::get('category/{slug}/designs', [Front\GraphicDesignsController::class, 'getGraphicDesigns'])->name('category.designs');

    Route::get('design/choose/{designHash}', [Front\GraphicDesignsController::class, 'chooseDesign'])->name('choose');
    Route::get('design/edit/{designHash}', [Front\GraphicDesignsController::class, 'editDesign'])->name('edit');
    Route::get('design/get/{designHash}', [Front\GraphicDesignsController::class, 'getUserDesign'])->name('get');
    Route::post('design/save/{designHash}', [Front\GraphicDesignsController::class, 'saveDesign'])->name('save');
    Route::post('design/save-final/{designHash}', [Front\GraphicDesignsController::class, 'saveFinal'])->name('saveFinal');

    Route::post('previews/list/get', [Front\GraphicDesignsController::class, 'getDesignPreviews'])->name('previews.list.get');

    Route::get('download/{designHash}', [Front\GraphicDesignsController::class, 'downloadDesign'])->name('download')->middleware('throttle:download');
    Route::get('download/{designHash}/package', [Front\GraphicDesignsController::class, 'downloadPackage'])->name('download.package')->middleware('throttle:download');
    Route::get('download/{designHash}/preview', [Front\GraphicDesignsController::class, 'downloadPreview'])->name('download.preview')->middleware('throttle:download');

    Route::get('palette/getPresetCategory', [Front\GraphicDesignsController::class, 'getPresetCategory'])->name('getPresetCategory');
});

Route::get('website/{id}/{slug}', [Root\PublicController::class, 'getWebsiteLink'])->name('web-link')->middleware('auth');
Route::get('media-content/{id}', [Root\PublicController::class, 'getMediaContent'])->name('media-content');

Route::name('test.')->prefix('test')->group(function () {
    Route::get('/', [TestController::class, 'index'])->name('index');
    Route::get('/screenshot', [TestController::class, 'screenshot'])->name('screenshot');
    Route::get('sections', [TestController::class, 'getSectionCategories'])->name('sections');
    Route::get('userData', [TestController::class, 'userData'])->name('user.data');
    Route::get('domains/{domain}', [TestController::class, 'domainCheck'])->name('domains');

    Route::get('cnamecheck', [DomainController::class, 'cnamecheck']);
})->middleware('admin');

Route::prefix('newsletter')->name('newsletter.')->group(function () {
    Route::get('template', [HomeController::class, 'template'])->name('template');
    Route::get('img', [HomeController::class, 'generateNewsletterImage'])->name('image');
    Route::post('upload-image', [HomeController::class, 'uploadNewsletterImage'])->name('uploadImage');
    Route::get('item/preview/{slug}', [HomeController::class, 'newsletterItemPreview'])->name('item.preview');
    Route::get('template/preview/{slug}', [HomeController::class, 'newsletterTemplatePreview'])->name('template.preview');
    Route::fallback(function () {
        $path = request()->path();
        $path = str_replace('newsletter/', '', $path);
        return redirect()->to(asset('vendor/mosaico/template/' . $path));
    });
});

Route::get('impClick/{id}', [HomeController::class, 'impClick'])->name('newsletter.impClick');

Route::get('ads.txt', function () {
    return "google.com, pub-6222751038700130, DIRECT, f08c47fec0942fa0";
});