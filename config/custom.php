<?php

return [
    'storage_name' => [
        'media_library' => 'media',
        'tinymce' => 'editor',
        'video' => 'video',
        'email' => 'email',
        'blog' => 'blog',
        'directory' => 'directory',
        'blogAds' => 'blog-ads',
        'siteAds' => 'site-ads',
        'portfolio' => 'portfolio',
        'page' => 'page',
        'setting' => 'setting',
    ],
    'variable' => [
        'max_image_size' => 10, //MB
        'blog_image_ratio_width' => 4,
        'blog_image_ratio_height' => 2,
        'tutorial_image_ratio_width' => 4,
        'tutorial_image_ratio_height' => 3,
        'package_image_ratio_width' => 3,
        'package_image_ratio_height' => 4,
        'portfolio_image_ratio_width' => 3,
        'portfolio_image_ratio_height' => 4,
    ],
    "template" => [
        "domain" => env('TEMPLATE_DOMAIN', 'bizinaboxtemplate.test'),
    ],
    'menu' => [
        'admin' => [
            ['title' => 'TODO List', 'route' => 'admin.todo.index', 'url' => 'admin/todo', 'hasCount' => true, 'icon' => 'flaticon-list-3'],
            ['title' => 'Dashboard', 'route' => 'admin.dashboard', 'url' => 'admin/dashboard', 'svg' => 'dashboard'],
            ['title' => 'Quick Tour', 'route' => 'admin.quick-tours.index', 'url' => 'admin/quick-tours*'],
            ['title' => 'Announcements', 'route' => 'admin.announcement.index', 'url' => 'admin/announcement*'],
            [
                'title' => 'Appointments', 'url' => 'admin/appointment*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    // ['title'=>'Available Dates', 'route'=>'admin.appointment.setting.index', 'url'=>'admin/appointment/setting*'],
                    // ['title'=>'Unavailable Dates', 'route'=>'admin.appointment.blockDate.index','params'=>["type"=>"appointment", "id"=>0],'url'=>'admin/appointment/unavailable-dates*'],
                    ['title' => 'Category', 'route' => 'admin.appointment.category.index', 'url' => 'admin/appointment/category*'],
                    ['title' => 'Listings', 'route' => 'admin.appointment.listing.index', 'url' => 'admin/appointment/listing*'],
                ],
            ],
            [
                'title' => 'Blog', 'url' => 'admin/blog/*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Front Setting', 'route' => 'admin.blog.front.index', 'url' => 'admin/blog/front*'],
                    ['title' => 'Setting', 'route' => 'admin.blog.setting.index', 'url' => 'admin/blog/setting*'],
                    ['title' => 'Packages', 'route' => 'admin.blog.package.index', 'url' => 'admin/blog/package*'],
                    ['title' => 'Categories', 'route' => 'admin.blog.category.index', 'url' => 'admin/blog/category*'],
                    ['title' => 'Tags', 'route' => 'admin.blog.tag.index', 'url' => 'admin/blog/tag*'],
                    ['title' => 'Posts', 'route' => 'admin.blog.post.index', 'url' => 'admin/blog/post*'],
                    ['title' => 'Authors', 'route' => 'admin.blog.author.index', 'url' => 'admin/blog/author*'],
                    ['title' => 'Comments', 'route' => 'admin.blog.comment.index', 'url' => 'admin/blog/comment*'],
                ],
            ],
            [
                'title' => 'Blog Advertisement', 'url' => 'admin/blogAds*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Ads Types', 'route' => 'admin.blogAds.type.index', 'url' => 'admin/blogAds/type*'],
                    ['title' => 'Ads Positions', 'route' => 'admin.blogAds.position.index', 'url' => 'admin/blogAds/position*'],
                    ['title' => 'Ads Spots', 'route' => 'admin.blogAds.spot.index', 'url' => 'admin/blogAds/spot*'],
                    ['title' => 'Ads Listings', 'route' => 'admin.blogAds.listing.index', 'url' => 'admin/blogAds/listing*'],
                ],
            ],
            [
                'title' => 'Content Management', 'url' => ['admin/review*', 'admin/slider*', 'admin/testimonial*', 'admin/legalPage*', 'admin/boxes*', 'admin/video', 'admin/welcome-video', 'admin/getting-started', 'admin/home-sliders*', 'admin/home/how-to-build*'], 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Reviews', 'route' => 'admin.review.index', 'url' => 'admin/review*'],
                    ['title' => 'Featured Slider', 'route' => 'admin.slider.index', 'url' => 'admin/slider*'],
                    ['title' => 'Testimonials', 'route' => 'admin.testimonial.index', 'url' => 'admin/testimonial*'],
                    ['title' => 'Legal Pages', 'route' => 'admin.legalPage.index', 'url' => 'admin/legalPage*'],
                    ['title' => 'Home Sliders', 'route' => 'admin.home-sliders.index', 'url' => 'admin/home-sliders*'],
                    ['title' => 'Boxes', 'route' => 'admin.boxes.index', 'url' => 'admin/boxes*'],
                    ['title' => 'Video', 'route' => 'admin.video.index', 'url' => 'admin/video'],
                    ['title' => 'Welcome Video', 'route' => 'admin.welcome-video.index', 'url' => 'admin/welcome-video'],
                    ['title' => 'Getting Started', 'route' => 'admin.getting-started.index', 'url' => 'admin/getting-started'],
                    ['title' => 'How To Build', 'route' => 'admin.home.how-to-build', 'url' => 'admin/home/how-to-build*'],
                ],
            ],
            [
                'title' => 'Directory Listing', 'url' => 'admin/directory/*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Front Setting', 'route' => 'admin.directory.front.index', 'url' => 'admin/directory/front*'],
                    ['title' => 'Directory Setting', 'route' => 'admin.directory.setting.index', 'url' => 'admin/directory/setting*'],
                    ['title' => 'Categories', 'route' => 'admin.directory.category.index', 'url' => 'admin/directory/category*'],
                    ['title' => 'Tags', 'route' => 'admin.directory.tag.index', 'url' => 'admin/directory/tag*'],
                    ['title' => 'Packages', 'route' => 'admin.directory.package.index', 'url' => 'admin/directory/package*'],
                    ['title' => 'Listings', 'route' => 'admin.directory.listing.index', 'url' => 'admin/directory/listing*'],
                ],
            ],
            [
                'title' => 'Domain', 'url' => 'admin/domain*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'TLDs', 'route' => 'admin.domainTld.index', 'url' => 'admin/domainTld*'],
                    ['title' => 'New Domain', 'route' => 'admin.domain.search', 'url' => ['admin/domain/search*', 'admin/domain/connect']],
                    ['title' => 'Transfer Domain', 'route' => 'admin.domain.transfer', 'url' => 'admin/domain/transfer*', 'disabled' => true],
                    ['title' => 'Purchased Domains', 'route' => 'admin.domainList.index', 'url' => 'admin/domainList*'],
                    ['title' => 'Connected Domains', 'route' => 'admin.domain.connectList', 'url' => 'admin/domain/connectList*'],
                ],
            ],
            [
                'title' => 'Email Campaign', 'url' => 'admin/email/*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Setting', 'route' => 'admin.directory.setting.index', 'url' => 'admin/email/setting*', 'disabled' => true],
                    ['title' => 'Packages', 'route' => 'admin.email.package.index', 'url' => 'admin/email/package*', 'disabled' => true],
                    ['title' => 'Category', 'route' => 'admin.email.category.index', 'url' => 'admin/email/category*'],
                    ['title' => 'Templates', 'route' => 'admin.email.template.index', 'url' => 'admin/email/template*'],
                    ['title' => 'Campaigns', 'route' => 'admin.email.campaign.index', 'url' => 'admin/email/campaign*'],
                    ['title' => 'Subscribers', 'route' => 'admin.email.subscriber.index', 'url' => 'admin/email/subscriber*'],
                ],
            ],
            [
                'title' => 'FAQ Management', 'url' => 'admin/faq*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'FAQ Category', 'route' => 'admin.faq.category.index', 'url' => 'admin/faq/category*'],
                    ['title' => 'FAQ Lists', 'route' => 'admin.faq.item.index', 'url' => 'admin/faq/item*'],
                ],
            ],
            [
                'title' => 'File Management', 'url' => 'admin/file*', 'hasSub' => true, 'svg' => 'layer', 'disabled' => true,
                'subMenu' => [
                    ['title' => 'Storage', 'route' => 'admin.file.storage.index', 'url' => 'admin/file/storage*'],
                    ['title' => 'Websites', 'route' => 'admin.file.website.index', 'url' => 'admin/file/website*'],
                ],
            ],
            [
                'title' => 'Graphic Designs', 'url' => 'admin/graphic*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Graphics', 'route' => 'admin.graphics.index', 'url' => 'admin/graphics'],
                    ['title' => 'Front Setting', 'route' => 'admin.graphics.front.index', 'url' => 'admin/graphics/front'],
                    ['title' => 'Categories', 'route' => 'admin.graphics.category.index', 'url' => 'admin/graphics/category'],
                    ['title' => 'Designs', 'route' => 'admin.graphics.design.index', 'url' => 'admin/graphics/design*'],
                    ['title' => 'User Designs', 'route' => 'admin.graphics.design.user-index', 'url' => 'admin/graphics/user-design*'],
                    ['title' => 'Masks', 'route' => 'admin.graphics.masks.view', 'url' => 'admin/graphics/masks*'],
                    ['title' => 'Icons', 'route' => 'admin.graphics.icons.view', 'url' => 'admin/graphics/icons*'],
                    ['title' => 'Images', 'route' => 'admin.graphics.images.view', 'url' => 'admin/graphics/images*'],
                    ['title' => 'Fonts', 'route' => 'admin.graphics.font.index', 'url' => 'admin/graphics/font*'],
                ],
            ],
            [
                'title' => 'Lacarte', 'url' => 'admin/lacarte*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Category', 'route' => 'admin.lacarte.category.index', 'url' => 'admin/lacarte/category*'],
                    ['title' => 'Items', 'route' => 'admin.lacarte.item.index', 'url' => 'admin/lacarte/item*'],
                ],
            ],
            [
                'title' => 'LiveChat', 'url' => 'admin/livechat*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Setting', 'route' => 'admin.livechat.setting.index', 'url' => 'admin/livechat/setting*'],
                    ['title' => 'Services', 'route' => 'admin.livechat.service.index', 'url' => 'admin/livechat/service*'],
                    ['title' => 'Chat Box', 'route' => 'admin.livechat.chat.index', 'url' => 'admin/livechat/chat*'],
                ],
            ],
            ['title' => 'Mail Service Management', 'route' => 'admin.mail.domain.index', 'url' => 'admin/mail*'],
            [
                'title' => 'Module', 'url' => 'admin/module*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Category', 'route' => 'admin.module.category.index', 'url' => 'admin/module/category*'],
                    ['title' => 'Items', 'route' => 'admin.module.item.index', 'url' => 'admin/module/item*'],
                ],
            ],
            [
                'title' => 'Newsletter', 'url' => 'admin/newsletter*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Template', 'route' => 'admin.newsletter.template.index', 'url' => 'admin/newsletter/template*'],
                    ['title' => 'Create a Newsletter', 'route' => 'admin.newsletter.item.index', 'url' => 'admin/newsletter/item*'],
                ],
            ],
            [
                'title' => 'Newsletter Advertisement', 'url' => 'admin/newsletterAds*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Ads Type', 'route' => 'admin.newsletterAds.type.index', 'url' => 'admin/newsletterAds/type*'],
                    ['title' => 'Ads Position', 'route' => 'admin.newsletterAds.position.index', 'url' => 'admin/newsletterAds/position*'],
                    ['title' => 'Ads Listing', 'route' => 'admin.newsletterAds.listing.index', 'url' => 'admin/newsletterAds/listing*'],
                ],
            ],
            [
                'title' => 'Notifications', 'url' => 'admin/notification*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Category', 'route' => 'admin.notification.category.index', 'url' => 'admin/notification/category*'],
                    ['title' => 'Template', 'route' => 'admin.notification.template.index', 'url' => 'admin/notification/template*'],
                ],
            ],
            ['title' => 'Packages', 'route' => 'admin.package.item.index', 'url' => 'admin/package*'],
            [
                'title' => 'Palette Ideas', 'url' => 'admin/palette-idea*', 'hasSub' => true, 'disabled' => true,
                'subMenu' => [
                    ['title' => 'Category', 'route' => 'admin.palette-idea.category.index', 'url' => 'admin/palette-idea/category'],
                    ['title' => 'Items', 'route' => 'admin.palette-idea.item.index', 'url' => 'admin/palette-idea/item*'],
                ],
            ],
            [
                'title' => 'Palettes', 'url' => 'admin/palettes*', 'hasSub' => true,
                'subMenu' => [
                    [
                        'title' => 'Simple', 'url' => 'admin/palettes/simple*', 'hasSub' => true,
                        'subMenu' => [
                            ['title' => 'Category', 'route' => 'admin.palettes.categories.view', 'params' => ['type' => 'simple'], 'url' => 'admin/palettes/simple/categories'],
                            ['title' => 'Palettes', 'route' => 'admin.palettes.view', 'params' => ['type' => 'simple'], 'url' => 'admin/palettes/simple/palettes'],
                        ],
                    ],
                    [
                        'title' => 'Advanced', 'url' => 'admin/palettes/advanced*', 'hasSub' => true,
                        'subMenu' => [
                            ['title' => 'Category', 'route' => 'admin.palettes.categories.view', 'params' => ['type' => 'advanced'], 'url' => 'admin/palettes/advanced/categories'],
                            ['title' => 'Palettes', 'route' => 'admin.palettes.view', 'params' => ['type' => 'advanced'], 'url' => 'admin/palettes/advanced/palettes'],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Plugin', 'url' => 'admin/plugin*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Category', 'route' => 'admin.plugin.category.index', 'url' => 'admin/plugin/category*'],
                    ['title' => 'Items', 'route' => 'admin.plugin.item.index', 'url' => 'admin/plugin/item*'],
                ],
            ],
            [
                'title' => 'Portfolio', 'url' => 'admin/portfolio*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Front Setting', 'route' => 'admin.portfolio.front.index', 'url' => 'admin/portfolio/front*'],
                    ['title' => 'Category', 'route' => 'admin.portfolio.category.index', 'url' => 'admin/portfolio/category*'],
                    ['title' => 'Items', 'route' => 'admin.portfolio.item.index', 'url' => 'admin/portfolio/item*'],
                ],
            ],
            [
                'title' => 'Purchase Follow-up', 'url' => ['admin/purchasefollowup*'], 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Emails', 'route' => 'admin.purchasefollowup.email.index', 'url' => 'admin/purchasefollowup/email*'],
                    ['title' => 'Forms', 'route' => 'admin.purchasefollowup.form.index', 'url' => 'admin/purchasefollowup/form*'],
                ],
            ],
            ['title' => 'Coupon', 'route' => 'admin.coupon.index', 'url' => 'admin/coupon*', 'svg' => 'layer'],
            [
                'title' => 'Purchase Management', 'url' => 'admin/purchase/*', 'hasSub' => true,
                'subMenu' => [
                    ['title' => 'Orders', 'route' => 'admin.purchase.order.index', 'url' => 'admin/purchase/order*'],
                    ['title' => 'Subscriptions', 'route' => 'admin.purchase.subscription.index', 'url' => 'admin/purchase/subscription*'],
                    ['title' => 'Transactions', 'route' => 'admin.purchase.transaction.index', 'url' => 'admin/purchase/transaction*'],
                    ['title' => 'Submitted Forms', 'route' => 'admin.purchase.form.index', 'url' => 'admin/purchase/form*'],
                    ['title' => 'User Products', 'route' => 'admin.purchase.package.index', 'url' => ['admin/purchase/package*', 'admin/purchase/readymade*', 'admin/purchase/blog*', 'admin/purchase/plugin*', 'admin/purchase/lacarte*'], 'icon' => 'm-menu__link-bullet--dot'],
                ],
            ],
            [
                'title' => 'Ready Made Biz', 'url' => 'admin/readymade*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Category', 'route' => 'admin.readymade.category.index', 'url' => 'admin/readymade/category*'],
                    ['title' => 'Items', 'route' => 'admin.readymade.item.index', 'url' => 'admin/readymade/item*'],
                ],
            ],
            [
                'title' => 'Service', 'url' => 'admin/service*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Category', 'route' => 'admin.service.category.index', 'url' => 'admin/service/category*'],
                    ['title' => 'Items', 'route' => 'admin.service.item.index', 'url' => 'admin/service/item*'],
                ],
            ],
            ['title' => 'Social', 'href' => 'https://social.bizinabox.com', 'blank' => true],
            [
                'title' => 'Team Management', 'url' => 'admin/teamManage*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Team Property', 'route' => 'admin.teamManage.property.index', 'url' => 'admin/teamManage/property*'],
                    ['title' => 'Teams', 'route' => 'admin.teamManage.team.index', 'url' => 'admin/teamManage/team*'],
                ],
            ],
            [
                'title' => 'Templates', 'url' => 'admin/template*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Front Setting', 'route' => 'admin.template.front.index', 'url' => 'admin/template/front*'],
                    ['title' => 'Category', 'route' => 'admin.template.category.index', 'url' => 'admin/template/category*'],
                    //                    ['title' => 'Headers', 'route' => 'admin.template.header.index', 'url' => 'admin/template/header*'],
                    //                    ['title' => 'Footers', 'route' => 'admin.template.footer.index', 'url' => 'admin/template/footer*'],
                    ['title' => 'Templates', 'route' => 'admin.template.item.index', 'url' => 'admin/template/item*'],
                    ['title' => 'Sections', 'route' => 'admin.template.section.index', 'url' => 'admin/template/section*'],
                ],
            ],
            [
                'title' => 'Themes', 'url' => 'admin/theme*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Category', 'route' => 'admin.theme.category.index', 'url' => 'admin/theme/category*'],
                    ['title' => 'Themes', 'route' => 'admin.theme.item.index', 'url' => 'admin/theme/item*'],
                ],
            ],
            [
                'title' => 'Tickets', 'url' => 'admin/ticket*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Category', 'route' => 'admin.ticket.category.index', 'url' => 'admin/ticket/category*'],
                    ['title' => 'Tickets', 'route' => 'admin.ticket.item.index', 'url' => 'admin/ticket/item*'],
                ],
            ],
            [
                'title' => 'Tutorials', 'url' => 'admin/tutorial*', 'hasSub' => true, 'svg' => 'layer',
                'subMenu' => [
                    ['title' => 'Category', 'route' => 'admin.tutorial.category.index', 'url' => 'admin/tutorial/category*'],
                    ['title' => 'Tutorials', 'route' => 'admin.tutorial.item.index', 'url' => 'admin/tutorial/item*'],
                ],
            ],
            ['title' => 'User Management', 'route' => 'admin.userManage.index', 'url' => 'admin/userManage'],
            [
                'title' => 'Videos', 'url' => ['admin/video/category*', 'admin/video/item*'], 'hasSub' => true,
                'subMenu' => [
                    ['title' => 'Category', 'route' => 'admin.video.category.index', 'url' => 'admin/video/category'],
                    ['title' => 'Items', 'route' => 'admin.video.item.index', 'url' => 'admin/video/item*'],
                ],
            ],
            ['title' => 'Websites', 'route' => 'admin.website.list.index', 'url' => 'admin/website*'],
            ['title' => 'Widgets', 'route' => 'admin.widget.index', 'url' => 'admin/widget*'],
            [
                'title' => 'Setting', 'url' => 'admin/setting*', 'hasSub' => true, 'svg' => 'layer', 'divider' => true,
                'subMenu' => [
                    ['title' => 'SEO Setting', 'route' => 'admin.setting.seo.index', 'url' => 'admin/setting/seo*'],
                    ['title' => 'Social Login', 'route' => 'admin.setting.social.index', 'url' => 'admin/setting/social*'],
                    ['title' => 'Payment', 'route' => 'admin.setting.payment.index', 'url' => 'admin/setting/payment*'],
                    ['title' => 'Third Party', 'route' => 'admin.setting.third_party.index', 'url' => 'admin/setting/third-party*'],
                    ['title' => 'FireWall', 'route' => 'admin.setting.firewall.index', 'url' => 'admin/setting/firewall*'],
                ],
            ],
        ],
    ],
];
