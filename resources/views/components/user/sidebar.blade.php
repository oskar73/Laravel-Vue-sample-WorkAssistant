@php
    $isGetStarted = user()->isGettingStarted();
    $websitePackageCount = user()->websitePackages->count();
    $websiteCount = user()->websites->count();
    $blogPackageCount = user()->blogPackages->count();
    $blogAdsListingCount = user()->blogAdsListings->count();
    $newsletterAdsListingCount = user()->newsletterAdsListings->count();

    $hasWebsiteBlogModule = user()->hasWebsiteBlogModule();
    $hasWebsiteAppointmentModule = user()->hasWebsiteAppointmentModule();
    $hasWebsiteEcommerceModule = user()->hasWebsiteEcommerceModule();

    $orderCount = user()->orders->count();
    $quickTourItems = App\Models\QuickTour::where('status', 1)
        ->orderBy('order')
        ->get();
    $quickTourItemsFormatted = [];

    foreach ($quickTourItems as $index => $item) {
        $quickTourItemsFormatted[$item->targetID] = $item;
    }

    $getIntroDetails = function ($targetId) use ($quickTourItemsFormatted) {
        $targets = optional($quickTourItemsFormatted);
        $target = $targets[$targetId];
        return $target ? 'data-title="' . $target->title . '" data-intro="' . $target->description . '"' : '';
    };

    $userMenu = [
        ['title' => 'Getting Started', 'route' => 'user.getting.started.index', 'url' => 'account/getting-started*', 'img' => asset('assets/img/dashboard.svg'), 'disabled' => !$isGetStarted],
        ['title' => 'TODO List', 'route' => 'user.todo.index', 'url' => 'account/todo', 'hasCount' => true, 'icon' => 'flaticon-list-3', 'disabled' => $isGetStarted || !$orderCount, 'intro' => $getIntroDetails('todo-list')],
        ['title' => 'Dashboard', 'route' => 'user.dashboard', 'url' => 'account/dashboard', 'svg' => 'dashboard', 'data-intro' => 'Browse your dashboard from here', 'data-step' => '1', 'disabled' => $isGetStarted, 'intro' => $getIntroDetails('dashboard')],
        ['title' => 'Quick Tour', 'route' => 'user.dashboard', 'url' => 'account/quick-tour', 'svg' => 'layer', 'data-intro' => 'Browse your dashboard from here', 'data-step' => '1', 'disabled' => $isGetStarted],
        ['title' => 'Appointments', 'url' => 'account/appointment*', 'hasSub' => true, 'svg' => 'layer', 'disabled' => $isGetStarted || !$hasWebsiteAppointmentModule, 'subMenu' => [['title' => 'My Appointments', 'route' => 'user.appointment.listing.index', 'url' => 'account/appointment/listing*'], ['title' => 'Site Category', 'route' => 'user.appointment.category.index', 'url' => 'account/appointment/category*'], ['title' => 'Site Appointment', 'route' => 'user.appointment.site-listing.index', 'url' => 'account/appointment/site-listing*']], 'intro' => $getIntroDetails('appointments')],
        ['title' => 'Bizinabox Blog', 'url' => 'account/blog*', 'hasSub' => true, 'svg' => 'layer', 'disabled' => $isGetStarted || !$hasWebsiteBlogModule, 'subMenu' => [['title' => 'Post Blog', 'route' => 'user.blog.index', 'url' => 'account/blog*']], 'intro' => $getIntroDetails('blogs')],
        ['title' => 'Blog Advertise', 'route' => 'user.blogAds.index', 'url' => 'account/blogAds*', 'svg' => 'layer', 'data-intro' => 'Navigate here to create advertise', 'data-step' => '3', 'disabled' => $isGetStarted || !$blogAdsListingCount, 'intro' => $getIntroDetails('blog-ads')],
        ['title' => 'Newsletter Advertise', 'route' => 'user.newsletterAds.index', 'url' => 'account/newsletterAds*', 'svg' => 'layer', 'data-intro' => 'Navigate here to create advertise', 'data-step' => '3', 'disabled' => $isGetStarted || !$newsletterAdsListingCount, 'intro' => $getIntroDetails('newsletter-ads')],
        ['title' => 'Community', 'href' => 'https://social.bizinabox.com', 'disabled' => $isGetStarted, 'svg' => 'layer', 'data-intro' => 'Browse your social dashboard from here', 'data-step' => '1'],
        ['title' => 'Forum', 'href' => 'https://community.bizinabox.com', 'disabled' => $isGetStarted, 'svg' => 'layer', 'data-intro' => 'Browse your community dashboard from here', 'data-step' => '1'],

        ['title' => 'Domain', 'disabled' => true, 'url' => 'account/domain*', 'hasSub' => true, 'svg' => 'layer', 'disabled' => $isGetStarted || !$websiteCount, 'subMenu' => [['title' => 'New Domain', 'route' => 'user.domain.search', 'url' => 'account/domain/*'], ['title' => 'My Domains', 'route' => 'user.domainList.index', 'url' => 'account/domainList*']], 'intro' => $getIntroDetails('domains')],
        ['title' => 'File Storage', 'disabled' => true, 'route' => 'user.file.index', 'url' => 'account/file*', 'svg' => 'layer', 'disabled' => $isGetStarted || !$websiteCount, 'intro' => $getIntroDetails('file-storage')],
        ['title' => 'Free Business Listing', 'disabled' => true, 'url' => 'account/directory*', 'hasSub' => true, 'svg' => 'layer', 'disabled' => $isGetStarted, 'subMenu' => [['title' => 'My Free Listing', 'route' => 'user.directory.index', 'url' => 'account/directory'], ['title' => 'View Directory', 'route' => 'directory.index', 'url' => 'account/directory']], 'intro' => $getIntroDetails('business-listings')],
        ['title' => 'Getting Started Website', 'disabled' => true, 'route' => 'user.website.getting.started', 'url' => 'account/website/getting-started', 'svg' => 'layer', 'disabled' => $isGetStarted || !$websitePackageCount, 'intro' => $getIntroDetails('getting-started-website')],
        [
            'title' => 'Graphic Designs',
            'disabled' => $isGetStarted,
            'url' => ['account/graphics*'],
            'hasSub' => true,
            'svg' => 'layer',
            'subMenu' => [
                [
                    'title' => 'My Graphic Designs',
                    'route' => 'user.graphics.index',
                    'url' => 'account/graphics*'
                ]
            ],
            'intro' => $getIntroDetails('graphic-designs')
        ],
        ['title' => 'Newsletter', 'url' => 'account/newsletter*', 'hasSub' => true, 'svg' => 'layer', 'disabled' => true, 'subMenu' => [['title' => 'Newsletter', 'route' => 'user.dashboard', 'url' => ''], ['title' => 'Subscriptions', 'route' => 'user.dashboard', 'url' => '']]],
        ['title' => 'Notifications', 'route' => 'user.ticket.index', 'url' => 'account/ticket*', 'svg' => 'layer', 'disabled' => true],
        ['title' => 'Newsletter Archive', 'route' => 'user.newsletter.archive', 'url' => 'account/newsletter/archive', 'svg' => 'layer', 'disabled' => false],
        ['title' => 'Portfolio', 'route' => 'user.portfolio.index', 'url' => 'account/portfolio*', 'svg' => 'layer', 'disabled' => $isGetStarted, 'intro' => $getIntroDetails('portfolio')],
        ['title' => 'Site Advertise', 'route' => 'user.dashboard', 'url' => 'account/quick-tour', 'svg' => 'layer', 'data-intro' => 'Browse your dashboard from here', 'data-step' => '1', 'disabled' => $isGetStarted],
        ['title' => 'Palettes', 'url' => 'account/palettes*', 'hasSub' => true, 'svg' => 'layer', 'disabled' => $isGetStarted || !$orderCount, 'subMenu' => [['title' => 'Simple', 'url' => 'account/palettes/simple*', 'hasSub' => true, 'subMenu' => [['title' => 'Category', 'route' => 'user.palettes.categories.view', 'params' => ['type' => 'simple'], 'url' => 'account/palettes/simple/categories'], ['title' => 'Palettes', 'route' => 'user.palettes.view', 'params' => ['type' => 'simple'], 'url' => 'account/palettes/simple/palettes']]], ['title' => 'Advanced', 'url' => 'account/palettes/advanced*', 'hasSub' => true, 'subMenu' => [['title' => 'Category', 'route' => 'user.palettes.categories.view', 'params' => ['type' => 'advanced'], 'url' => 'account/palettes/advanced/categories'], ['title' => 'Palettes', 'route' => 'user.palettes.view', 'params' => ['type' => 'advanced'], 'url' => 'account/palettes/advanced/palettes']]]], 'intro' => $getIntroDetails('palettes')],
        ['title' => 'Product Management', 'disabled' => true, 'url' => 'account/product*', 'hasSub' => true, 'svg' => 'layer', 'disabled' => $isGetStarted || !$hasWebsiteEcommerceModule, 'subMenu' => [['title' => 'Products', 'route' => 'user.product.index', 'url' => 'account/product'], ['title' => 'Category', 'route' => 'user.product.category.index', 'url' => 'account/product/category*'], ['title' => 'Sub-Category', 'route' => 'user.product.sub.category.index', 'url' => 'account/product/sub-category*'], ['title' => 'Units', 'route' => 'user.product.unit.index', 'url' => 'account/product/unit*'], ['title' => 'Color', 'route' => 'user.product.color.index', 'url' => 'account/product/color*'], ['title' => 'Size', 'route' => 'user.product.size.index', 'url' => 'account/product/size*'], ['title' => 'Coupon', 'route' => 'user.product.coupon.index', 'url' => 'account/product/coupon*']], 'intro' => $getIntroDetails('purchase-management')],
        [
            'title' => 'Themes',
            'disabled' => true,
            'url' => 'account/theme*',
            'hasSub' => true,
            'svg' => 'layer',
            'disabled' => $isGetStarted || !$orderCount,
            'subMenu' => [
                ['title' => 'Category', 'route' => 'user.theme.category.index', 'url' => 'account/theme/category*'],
                ['title' => 'Items', 'route' => 'user.theme.item.index', 'url' => 'account/theme/item*'],
                // ['title' => 'Sections', 'route' => 'user.template.section.index', 'url' => 'account/template/section*']
            ],
            'intro' => $getIntroDetails('themes')
        ],
        ['title' => 'Help Center', 'disabled' => true, 'route' => 'user.tutorial.index', 'url' => 'account/tutorial*', 'svg' => 'layer', 'disabled' => $isGetStarted, 'intro' => $getIntroDetails('tutorials')],
        ['title' => 'Websites', 'disabled' => true, 'route' => 'user.website.index', 'url' => 'account/website*', 'notUrl' => 'account/website/getting-started', 'svg' => 'layer', 'disabled' => $isGetStarted || !$websiteCount, 'intro' => $getIntroDetails('websites')],

        ['title' => 'Purchase Management', 'url' => 'account/purchase*', 'hasSub' => true, 'svg' => 'layer', 'disabled' => $isGetStarted || !$orderCount, 'subMenu' => [['title' => 'Orders', 'route' => 'user.purchase.order.index', 'url' => 'account/purchase/order*'], ['title' => 'Subscriptions', 'route' => 'user.purchase.subscription.index', 'url' => 'account/purchase/subscription*'], ['title' => 'Transactions', 'route' => 'user.purchase.transaction.index', 'url' => 'account/purchase/transaction*'], ['title' => 'Forms', 'route' => 'user.purchase.form.index', 'url' => 'account/purchase/form*'], ['title' => 'Products', 'route' => 'user.purchase.package.index', 'url' => ['account/purchase/package*', 'account/purchase/readymade*', 'account/purchase/blog*', 'account/purchase/lacarte*', 'account/purchase/plugin*', 'account/purchase/service*']]], 'intro' => $getIntroDetails('purchase-management')],
        ['title' => 'Tickets', 'route' => 'user.ticket.index', 'url' => 'account/ticket*', 'svg' => 'layer', 'disabled' => $isGetStarted, 'intro' => $getIntroDetails('tickets')],
        ['title' => 'Settings', 'route' => 'user.setting.index', 'url' => 'account/setting*', 'svg' => 'layer', 'disabled' => $isGetStarted, 'intro' => $getIntroDetails('setting')],
    ];

    if(!(user()->isPasswordForceUpdateNeed())){

    }

@endphp


<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
    <i class="la la-close"></i>
</button>
<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-light ">

    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-light m-aside-menu--submenu-skin-light position-static"
         m-menu-vertical="1" m-menu-scrollable="1" m-menu-dropdown-timeout="500"
    >
        <ul class="m-menu__nav ">
            @foreach ($userMenu as $menu)
                <x-global.menu-item :menu="$menu" />
            @endforeach
            <li class="m-menu__item" aria-haspopup="true">
                <a href="javascript:void(0);" class="m-menu__link"
                   onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                >
                    <i class="m-menu__link-icon fa fa-sign-out-alt"></i>
                    <span class="m-menu__link-text">Log out</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- END: Aside Menu -->
</div>
<!-- END: Left Aside -->
