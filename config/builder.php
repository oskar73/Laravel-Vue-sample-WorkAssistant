<?php

return [
    'version' => 1,

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Default Template/Website Data
    |-------------------------------------------------------------------------------------------------------------------
    | When we create a new Template or user creates a new website without template, they take this value as default for
    | Theme, Setting, Header, Footer
    */

    'default' => [
        "name" => "Bizinabox",
        "theme" => [
            'palettes' => [
                [
                    'name' => 'default',
                    'appliedTo' => 'website',
                    'colors' => [
                        'backgroundColor' => '#ffffff',
                        'primaryColor' => '#E07F10',
                        'buttonColor' => '#E07F10',
                        'socialIconColor' => '#11638F',
                        'headingColor' => '#1e7730',
                        'boxColor' => '#939393',
                        'secondaryColor' => '#11638F',
                    ],
                ],
            ],
            'font' => [
                'title' => [
                    'fontFamily' => 'Roboto',
                    'bold' => true,
                    'italic' => false,
                    'underline' => false,
                    'opacity' => 100,
                    'color' => null,
                    'fontSize' => 32,
                    'letterSpace' => 1,
                ],
                'subtitle' => [
                    'fontFamily' => 'Roboto',
                    'bold' => true,
                    'italic' => false,
                    'underline' => false,
                    'opacity' => 100,
                    'color' => null,
                    'fontSize' => 20,
                    'letterSpace' => 1,
                ],
                'description' => [
                    'fontFamily' => 'Roboto',
                    'bold' => false,
                    'italic' => false,
                    'underline' => false,
                    'opacity' => 100,
                    'color' => null,
                    'fontSize' => 16,
                    'letterSpace' => 1,
                ],
            ],
            'link' => [
                'header' => [
                    'type' => 'text',
                    'text' => [
                        'family' => 'Roboto',
                        'bold' => false,
                        'italic' => false,
                        'underline' => false,
                        'fontSize' => 14,
                        'letterSpace' => 5,
                        'color' => '',
                        'hoverColor' => '',
                        'opacity' => 100,
                        'hoverOption' => 'color_change_only',
                    ],
                    'button' => [
                        'bgColor' => '',
                        'color' => '',
                        'round' => 4,
                        'size' => 2,
                        'opacity' => 100,
                        'hoverOpacity' => 100,
                        'outline' => false,
                        'hoverOutline' => false,
                    ],
                ],
                'footer' => [
                    'type' => 'text',
                    'text' => [
                        'type' => 'text',
                        'family' => 'Roboto',
                        'bold' => false,
                        'italic' => false,
                        'underline' => false,
                        'fontSize' => 14,
                        'letterSpace' => 5,
                        'color' => '',
                        'hoverColor' => '',
                        'opacity' => 100,
                        'hoverOption' => 'color_change_only',
                    ],
                    'button' => [
                        'bgColor' => '',
                        'color' => '',
                        'round' => 4,
                        'size' => 2,
                        'opacity' => 100,
                        'hoverOpacity' => 100,
                        'outline' => false,
                        'hoverOutline' => false,
                    ],
                ],
                'main' => [
                    'text' => [
                        'type' => 'text',
                        'family' => 'Roboto',
                        'bold' => false,
                        'italic' => false,
                        'underline' => false,
                        'fontSize' => 14,
                        'letterSpace' => 5,
                        'color' => '',
                        'hoverColor' => '',
                        'opacity' => 100,
                        'hoverOption' => 'color_change_only',
                    ],
                    'button' => [
                        'bgColor' => '',
                        'color' => '',
                        'round' => 4,
                        'size' => 2,
                        'opacity' => 100,
                        'hoverOpacity' => 100,
                        'outline' => false,
                        'hoverOutline' => false,
                    ],
                ],
            ],
            'button' => [
                'round' => 4,
                'padding' => 1,
                'outline' => false,
                'hoverOutline' => false,
                'bgColor' => '',
                'hoverBgColor' => '',
                'color' => '',
                'hoverColor' => '',
                'hoverOpacity' => 100,
                'fontFamily' => 'Roboto',
                'bold' => false,
                'italic' => false,
                'underline' => false,
                'fontSize' => 14,
                'letterSpace' => 1,
                'fontOpacity' => 100,
            ],
            'socialIcon' => [
                'header' => [
                    'individual' => false,
                    'group' => [
                        'noOutline' => false,
                        'outlineCorner' => 4,
                        'iconSize' => 24,
                        'iconColor' => null,
                        'outlineColor' => null,
                        'outlineOnly' => false,
                        'hoverOpacity' => 100,
                    ],
                    'bizinabox' => [
                        'noOutline' => false,
                        'outlineCorner' => 4,
                        'iconSize' => 24,
                        'iconColor' => null,
                        'outlineColor' => null,
                        'outlineOnly' => false,
                        'hoverOpacity' => 100,
                    ],
                ],
                'main' => [
                    'individual' => false,
                    'group' => [
                        'noOutline' => false,
                        'outlineCorner' => 4,
                        'iconSize' => 24,
                        'iconColor' => null,
                        'outlineColor' => null,
                        'outlineOnly' => false,
                        'hoverOpacity' => 100,
                    ],
                ],
                'footer' => [
                    'individual' => false,
                    'group' => [
                        'noOutline' => false,
                        'outlineCorner' => 4,
                        'iconColor' => null,
                        'iconSize' => 24,
                        'outlineColor' => null,
                        'outlineOnly' => false,
                        'hoverOpacity' => 100,
                    ],
                ],
            ],
        ],
        "setting" => [
            "socialAccounts" => [
                "bizinabox" => ['url' => "https://www.bizinabox.com", 'show' => true],
                "facebook" => ['url' => "https://facebook.com", 'show' => true],
                "instagram" => ['url' => "https://instagram.com", 'show' => true],
                "linkedin" => ['url' => "https://linkedin.com", 'show' => true],
                "pinterest" => ['url' => "https://pinterest.com", 'show' => true],
                "reddit" => ['url' => "https://www.reddit.com", 'show' => true],
                "tiktok" => ['url' => "https://www.tiktok.com", 'show' => true],
                "twitter" => ['url' => "https://twitter.com", 'show' => true],
                "youtube" => ['url' => "https://youtube.com", 'show' => true],
            ],
            "businesses" => [
                [
                    "companyName" => "Bizinabox",
                    "address" => "260-C North El Camino Real",
                    "zipCode" => "92024",
                    "city" => "Encinitas",
                    "state" => "California",
                    "country" => "United States of America",
                    "contact" => [
                        "email" => "",
                        "phoneNumber" => "9807",
                    ],
                    "businessHours" => [
                        "monday" => [
                            "type" => "open",
                            "label" => "open",
                        ],
                        "tuesday" => [
                            "type" => "open",
                            "label" => "open",
                        ],
                        "wednesday" => [
                            "type" => "open",
                            "label" => "open",
                        ],
                        "thursday" => [
                            "type" => "open",
                            "label" => "open",
                        ],
                        "friday" => [
                            "type" => "open",
                            "label" => "open",
                        ],
                        "saturday" => [
                            "type" => "closed",
                            "label" => "closed",
                        ],
                        "sunday" => [
                            "type" => "closed",
                            "label" => "closed",
                        ],
                    ],
                ],
            ],
            "googleAnalyticsTrackingId" => "",
            "termsOfService" => "",
            "privacyPolicy" => "",
            "cookieBannerPosition" => "bottom",
            "bannerText" => "This site uses cookies",
            "agreeButtonText" => "I am okay with that",
            "headHTML" => "",
            "bodyEndHTML" => "",
        ],
        "header" => [
            "name" => "Header1",
            "data" => [
                "data" => [
                    "elements" => [
                        "siteTitle" => "",
                        "headerButton" => ["title" => "Call"],
                    ],
                ],
                "setting" => [
                    "layout" => 1,
                    "elements" => [
                        "siteTitle" => false,
                        "headerButton" => true,
                        "registerButton" => true,
                        "loginButton" => true
                    ],
                    "header" => [
                        "fixedNavigationBar" => true,
                        "scaleTextToWidth" => true,
                    ],
                    "visible" => true,
                ],
                "background" => [
                    "type" => "color",
                    "size" => "auto",
                ],
            ],
        ],
        "footer" => [
            "name" => "Footer1",
            "data" => [
                "data" => [
                    "elements" => [
                        "descriptionTitle" => "About Us",
                        "description" => "Add a description here",
                        "siteTitle" => "Site title here",
                        "title" => "Pate Title",
                        "subtitle" => "Click here to edit your subtitle",
                        "addressTitle" => "Address",
                    ],
                ],
                "setting" => [
                    "layout" => 2,
                    "elements" => [
                        "siteTitle" => false,
                        "subtitle" => true,
                        "phoneNumber" => true,
                        "email" => true,
                        "socialIcons" => true,
                        "description" => true,
                        "copyrightMessage" => true,
                        "address" => true,
                        "pagesMenu" => true,
                        "dividerLine" => true,
                    ],
                    "businessInformation" => [
                        "location" => "",
                    ],
                    "visible" => true,
                ],
                "background" => [
                    "type" => "color",
                    "size" => "auto",
                ],
            ],
        ],
    ],

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Template/Website Builder section categories and sections
    |-------------------------------------------------------------------------------------------------------------------
    | Section is a build unit of the website builder.
    | A website has multiple pages, a page has multiple sections. A section represent a Vue component in Bizinabox.
    | Whenever we add new section category or sections, we build Vue components for the sections.
    | The section has default settings & data for preview purpose or when a user use it for the first time.
    | We will migrate new sections when we deploy new version of sections to the server by running command
    | php artisan refresh:section
    | This command will delete all the old categories and sections from the database and migrate new ones.
    */

    'sections' => [
        [
            "name" => "Text",
            "description" => "Text Category",
            "module" => null,
            "data" => [
                "items" => [
                    "item" => [
                        "title" => "Add a title",
                        "description" =>
                            "You can use this element to explain to visitors what you do or inform them about other subjects. For instance, what is your passion and why or what does your company offer, i.e. products and services. You can hide this element in the menu on the right",
                        "buttons" => [["title" => "Read more"]],
                    ],
                    "count" => 2,
                ],
                "elements" => [
                    "title" => "Welcome to our website",
                    "subtitle" => "Learn more about what we do",
                    "description" =>
                        "You can edit text on your website by double clicking on a text box on your website. Alternatively, when you select a text box a settings menu will appear. Selecting Edit Text from this menu will also allow you to edit the text within this text box. Remember to keep your wording friendly, approachable and easy to understand as if you were talking to your customer. You can edit text on your website by double clicking on a text box on your website. Alternatively, when you select a text box a settings menu will appear. Selecting Edit Text from this menu will also allow you to edit the text within this text box. Remember to keep your wording friendly, approachable and easy to understand as if you were talking to your customer",
                    "buttons" => [["title" => "Read more"]],
                ],
            ],
            "limit_per_page" => 2,
            "sections" => [
                [
                    "name" => "Text1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "alignments" => ["left", "right"],
                            "alignment" => "left",
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],
                            "listElements" => [
                                "title" => true,
                                "description" => true,
                                "buttons" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Text2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "columns" => [1, 2, 3],
                            "column" => 2,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "layouts" => [
                                "contentAlignment" => "left",
                                "sectionSize" => "l",
                            ],
                            "listElements" => [
                                "title" => true,
                                "description" => true,
                                "buttons" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Text3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "column" => 2,
                            "columns" => [1, 2, 3],
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "layouts" => [
                                "shadow" => true,
                                "contentAlignment" => "left",
                                "sectionSize" => "l",
                            ],
                            "listElements" => [
                                "title" => true,
                                "description" => true,
                                "buttons" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Text4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "columns" => [1, 2],
                            "column" => 2,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "listElements" => [
                                "title" => true,
                                "description" => true,
                                "buttons" => true,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                                "shadow" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Text5",
                    "data" => [
                        "setting" => [
                            "layout" => 5,
                            "layouts" => ["alignment" => "left"],
                            "listElements" => [
                                "title" => true,
                                "description" => true,
                                "buttons" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Text6",
                    "data" => [
                        "setting" => [
                            "layout" => 6,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                                "buttons" => true,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Text7",
                    "data" => [
                        "setting" => [
                            "layout" => 7,
                            "alignments" => ["left", "right"],
                            "alignment" => "left",
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                                "buttons" => true,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Gallery",
            "description" => "Gallery Category",
            "module" => null,
            "data" => [
                "items" => [
                    "item" => [
                        "title" => "Item title1",
                        "image" => ["src" => ""],
                    ],
                    "count" => 3,
                ],
                "elements" => [
                    "title" => "Gallery",
                    "subtitle" => "Our latest and best photos",
                    "description" =>
                        "We love to take pictures and show them to the world",
                ],
            ],
            "limit_per_page" => 2,
            "sections" => [
                [
                    "name" => "Gallery1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "columns" => [3, 4, 6],
                            "column" => 3,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "contentAlignment" => "left",
                                "shape" => "square",
                                "spacing" => true,
                            ],
                            "listElements" => ["title" => true],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Gallery2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "columns" => [3, 4, 6],
                            "column" => 3,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "contentAlignment" => "left",
                                "shape" => "square",
                                "spacing" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Gallery3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "contentAlignment" => "left",
                                "shape" => "square",
                                "spacing" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Address",
            "description" => "Address Category",
            "module" => null,
            "data" => [
                "elements" => [
                    "title" => "Click here to edit your title",
                    "subtitle" => "Click here to edit your subtitle",
                    "description" =>
                        "Click here to edit your description",
                    "address" => "Address",
                    "phone" => "Phone",
                    "email" => "Email",
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Address1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "alignments" => ["left", "right"],
                            "alignment" => "left",
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                                "phoneNumber" => true,
                                "email" => true,
                                "dividerLine" => true,
                                "businessHours" => true,
                                "address" => true,
                            ],
                            "map" => ["zoomLevel" => 15, "type" => "roadmap"],
                            "businessInformation" => ["location" => ""],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Address2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "alignments" => ["left", "right"],
                            "alignment" => "left",
                            "elements" => [
                                "phoneNumber" => true,
                                "email" => true,
                                "address" => true,
                            ],
                            "map" => ["zoomLevel" => 15, "type" => "roadmap"],
                            "businessInformation" => ["location" => ""],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Address3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "alignments" => ["left", "right"],
                            "alignment" => "left",
                            "elements" => [
                                "phoneNumber" => true,
                                "email" => true,
                                "address" => true,
                            ],
                            "layouts" => ["sectionSize" => "l"],
                            "map" => ["zoomLevel" => 15, "type" => "roadmap"],
                            "businessInformation" => ["location" => ""],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Address4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "alignments" => ["left", "right"],
                            "alignment" => "left",
                            "elements" => [
                                "phoneNumber" => true,
                                "email" => true,
                                "address" => true,
                            ],
                            "map" => ["zoomLevel" => 15, "type" => "roadmap"],
                            "businessInformation" => ["location" => ""],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Address5",
                    "data" => [
                        "setting" => [
                            "layout" => 5,
                            "alignments" => ["left", "right"],
                            "alignment" => "left",
                            "elements" => [
                                "phoneNumber" => true,
                                "email" => true,
                                "address" => true,
                            ],
                            "map" => ["zoomLevel" => 15, "type" => "roadmap"],
                            "businessInformation" => ["location" => ""],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Business hours",
            "description" => "Business hours Category",
            "module" => null,
            "data" => [
                "elements" => [
                    "title" => "Business Hours",
                    "subtitle" => "Come visit us",
                    "description" =>
                        "You can edit text on your website by clicking on a text box on your website. If you have any questions, please visit our support Center where we have lots of helpful articles that will assist you in creating the website of your dreams!",
                    "address" => "Address",
                    "phone" => "Phone",
                    "email" => "Email",
                    "image" => ["src" => ""],
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "BusinessHours1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "alignment" => "left",
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                                "phoneNumber" => true,
                                "email" => true,
                                "dividerLine" => true,
                                "businessHours" => true,
                                "address" => true,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],
                            "map" => ["zoomLevel" => 15, "type" => "roadmap"],
                            "businessInformation" => ["location" => ""],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "BusinessHours2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],
                            "businessInformation" => ["location" => ""],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "BusinessHours3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                                "dividerLine" => true,
                                "address" => true,
                                "phoneNumber" => true,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],
                            "businessInformation" => ["location" => ""],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "BusinessHours4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "alignment" => "left",
                            "elements" => [
                                "image" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => ["sectionSize" => "l"],
                            "businessInformation" => ["location" => ""],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "BusinessHours5",
                    "data" => [
                        "setting" => [
                            "layout" => 5,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],
                            "businessInformation" => ["location" => ""],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "BusinessHours6",
                    "data" => [
                        "setting" => [
                            "layout" => 6,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "alignment" => "center",
                                "sectionSize" => "xl",
                            ],
                            "businessInformation" => ["location" => ""],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Call to action",
            "description" => "Call to action Category",
            "status" => 0,
            "module" => null,
            "data" => [
                "elements" => [
                    "icon" => "fa fa-home",
                    "image" => ["src" => ""],
                    "title" => "Welcome to our website",
                    "subtitle" => "Lean more about what we do",
                    "description" =>
                        "Explain your call to action or hide this element by clicking the option on the right panel.",
                    "buttons" => [["title" => "Read more"]],
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "CallToAction1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                                "buttons" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "CallToAction2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "elements" => [
                                "icon" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                                "buttons" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "CallToAction3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "alignment" => "left",
                            "elements" => [
                                "image" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                                "buttons" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "CallToAction4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "elements" => [
                                "icon" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                                "buttons" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "CallToAction5",
                    "data" => [
                        "setting" => [
                            "layout" => 5,
                            "alignment" => "left",
                            "elements" => [
                                "image" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                                "buttons" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                            ],

                        ],
                        "background" => [
                            "type" => "image",
                            "image" => "",
                        ],
                    ],
                ],
                [
                    "name" => "CallToAction6",
                    "data" => [
                        "setting" => [
                            "layout" => 6,
                            "alignment" => "left",
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                                "buttons" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Carousel",
            "description" => "Carousel Category",
            "module" => null,
            "data" => [
                "items" => [
                    "item" => [
                        "title" => "Type your text",
                        "subtitle" => "Type your text",
                        "description" => "Type your text",
                        "buttons" => [["title" => "more"]],
                        "image" => ["src" => ""],
                    ],
                    "count" => 3,
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Carousel1",
                    "data" => [
                        "setting" => [
                            "layouts" => [
                                "animation" => "bouncy",
                                "interval" => 4,
                                "fullSize" => false,
                                "alignment" => "center",
                                "sectionSize" => "l",
                            ],
                            "elements" => [
                                "navigation" => true,
                                "autoPlay" => false,
                            ],
                            "listElements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                                "buttons" => true,
                            ],
                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Countdown",
            "description" => "Countdown Category",
            "module" => null,
            "data" => [
                "elements" => [
                    "title" => "Countdown",
                    "subtitle" => "Our nex release will be there soon!",
                    "description" =>
                        "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Provident velit, eius voluptatem quasi quas maxime.",
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "CountDown1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                            ],

                        ],
                        "background" => [
                            "type" => "image",
                            "image" => "",
                        ],
                    ],
                ],
                [
                    "name" => "CountDown2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "shadow" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "CountDown3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                            ],

                        ],
                        "background" => [
                            "type" => "image",
                            "image" => "",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Contact form",
            "description" => "Contact form Category",
            "module" => null,
            "data" => [
                "elements" => [
                    "title" => "Contact me",
                    "subtitle" => "Get in touch",
                    "description" => "Write something in this area.",
                    "image" => ["src" => ""],
                    "phone" => "Phone",
                    "email" => "Email",
                ],
                "form" => [
                    "formFields" => [
                        "firstName" => [
                            "label" => "First Name",
                            "enabled" => true,
                        ],
                        "lastName" => [
                            "label" => "Last Name",
                            "enabled" => true,
                        ],
                        "subject" => [
                            "label" => "Subject",
                            "enabled" => true,
                        ],
                        "message" => [
                            "label" => "Message",
                            "enabled" => true,
                        ],
                        "email" => [
                            "label" => "Email",
                            "enabled" => true,
                        ],
                        "phone" => [
                            "label" => "Phone",
                            "enabled" => false,
                        ],
                        "date" => [
                            "label" => "Date",
                            "enabled" => false,
                        ],
                        "address" => [
                            "label" => "Address",
                            "enabled" => false,
                        ],
                    ],
                    "formAddress" => "",
                    "successMessage" => [
                        "title" => "Message Sent!",
                        "message" =>
                            "Your message has been sent successfully, I hope to respond within 24 hours. You can also contact us through social media, links can be found below!",
                    ],
                    "permissionMessage" =>
                        "By checking this box and submitting your information, you are granting us permission to email you. You may unsubscribe at any time.",
                ],
                "submitButton" => ["title" => "Send Message"],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "ContactForm1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "ContactForm2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                                "dividerLine" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                            ],

                        ],
                        "background" => [
                            "type" => "image",
                            "image" => "",
                        ],
                    ],
                ],
                [
                    "name" => "ContactForm3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "alignment" => "left",
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                            ],

                        ],
                        "background" => [
                            "type" => "image",
                            "image" => "",
                        ],
                    ],
                ],
                [
                    "name" => "ContactForm4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "alignment" => "left",
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "ContactForm5",
                    "data" => [
                        "setting" => [
                            "layout" => 5,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => ["alignment" => "left", "shadow" => true],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "ContactForm6",
                    "data" => [
                        "setting" => [
                            "layout" => 6,
                            "alignment" => "left",
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                                "phone" => true,
                                "email" => true,
                                "address" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                            ],
                            "businessInformation" => ["location" => ""],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "ContactForm7",
                    "data" => [
                        "setting" => [
                            "layout" => 7,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "ContactForm8",
                    "data" => [
                        "setting" => [
                            "layout" => 8,
                            "alignment" => "left",
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                            ],

                        ],
                        "background" => [
                            "type" => "image",
                            "image" => "",
                        ],
                    ],
                ]
            ],
        ],
        [
            "name" => "Cover Image",
            "description" => "Cover Image Category",
            "module" => null,
            "data" => [
                "elements" => [
                    "title" => "Welcome to our website",
                    "subtitle" => "Lean more about what we do",
                    "description" => "Add a description here.",
                    "image" => ["src" => "logotype_1.png"],
                    "buttons" => [["title" => "Read More"]],
                    "button" => ["title" => "Read More"],
                ],
                "meta" => [
                    "image_1" => [
                        "src" => "https://images.unsplash.com/photo-1573246123716-6b1782bfc499?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3wzMjE5NjZ8MHwxfHNlYXJjaHw4fHxmcnVpdHN8ZW58MHx8fHwxNjg2NDAyOTMwfDA&ixlib=rb-4.0.3&q=80&w=1080",
                        "maskImage" => "https://s3.amazonaws.com/storage.bizinabox.com/assets/img/masks/top-left-rounded.svg",
                    ],
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "CoverImage1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "alignment" => "left",
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                                "buttons" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "fullPage" => false,
                            ],
                        ],
                        "background" => [
                            "type" => "image",
                            "image" => "",
                        ],
                    ],
                ],
                [
                    "name" => "CoverImage2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "alignment" => "left",
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                                "buttons" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "fullPage" => false,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "CoverImage3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                                "buttons" => true,
                            ],
                            "layouts" => ["fullPage" => false],

                        ],
                        "background" => [
                            "type" => "image",
                            "image" => "",
                        ],
                    ],
                ],
                [
                    "name" => "CoverImage4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                                "buttons" => true,
                            ],
                            "layouts" => [
                                "fullPage" => false,
                                "alignment" => "center",
                                "sectionSize" => "l",
                            ],

                        ],
                        "background" => [
                            "type" => "image",
                            "image" => "",
                        ],
                    ],
                ],
                [
                    "name" => "CoverImage5",
                    "data" => [
                        "setting" => [
                            "layout" => 5,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                                "buttons" => true,
                                "image" => true,
                            ],
                            "layouts" => [
                                "fullPage" => false,
                                "alignment" => "center",
                                "sectionSize" => "l",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "CoverImage6",
                    "data" => [
                        "setting" => [
                            "layout" => 6,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                                "button" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                            ],
                        ],
                        "background" => [
                            "type" => "image",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "FAQ",
            "description" => "FAQ Category",
            "module" => null,
            "data" => [
                "elements" => [
                    "title" => "Frequently Asked Questions",
                    "subtitle" => "Edit your subtitle",
                    "description" =>
                        "You can edit text on your website by clicking on a text box on your website.",
                ],
                "items" => [
                    "item" => [
                        "question" =>
                            "What primary services do you offer?",
                        "answer" => "Add an answer here",
                    ],
                    "count" => 4,
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Faq1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "columns" => [1, 2],
                            "column" => 1,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Faq2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "columns" => [1, 2],
                            "column" => 2,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "shadow" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Faq3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "columns" => [1, 2],
                            "column" => 2,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Faq4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "columns" => [1, 2],
                            "column" => 1,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Faq5",
                    "data" => [
                        "setting" => [
                            "layout" => 5,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Iframe",
            "description" => "Iframe Category",
            "module" => null,
            "status" => false,
            "data" => [],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Iframe1",
                    "data" => [
                        "setting" => [
                            "elements" => ["width" => 500, "height" => 500],
                            "layouts" => [
                                "fullPage" => false,
                                "alignment" => "center",
                            ],
                            "iframe" => ["url" => "https://example.com"],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Map",
            "description" => "Map Category",
            "status" => 0,
            "module" => null,
            "data" => [],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Map1",
                    "data" => [
                        "setting" => [
                            "layouts" => ["fullWidth" => true],
                            "map" => [
                                "markers" => [],
                                "manageMakers" => true,
                                "zoomLevel" => 15,
                                "type" => "roadmap",
                                "mapTypes" => [
                                    "roadmap",
                                    "satellite",
                                    "hybrid",
                                    "terrain",
                                ],
                                "grayscale" => false,
                                "zoomControl" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Media/list",
            "description" => "Media/list Category",
            "module" => null,
            "data" => [
                "elements" => [
                    "title" => "Welcome to our website",
                    "subtitle" => "Click here to edit your subtitle",
                    "description" => "Add your description here.",
                ],
                "items" => [
                    "item" => [
                        "title" => "Type your text",
                        "subtitle" => "Type your text",
                        "description" => "Type your text",
                        "image" => ["src" => ""],
                    ],
                    "count" => 3,
                ],
            ],
            "limit_per_page" => 2,
            "sections" => [
                [
                    "name" => "MediaList1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "columns" => [2, 3, 4],
                            "column" => 3,
                            "elements" => [
                                "title" => true,
                                "description" => true,
                                "subtitle" => false,
                            ],
                            "layouts" => [
                                "shadow" => true,
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "aspectRatios" => [
                                    "landscape",
                                    "portrait",
                                    "square",
                                ],
                                "aspectRatio" => "landscape",
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "MediaList2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "columns" => [2, 3, 4],
                            "column" => 3,
                            "elements" => [
                                "title" => true,
                                "description" => true,
                                "subtitle" => false,
                            ],
                            "layouts" => [
                                "shadow" => false,
                                "sectionSize" => "l",
                                "alignment" => "center",
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "MediaList3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "columns" => [1, 2],
                            "column" => 1,
                            "elements" => [
                                "title" => true,
                                "description" => true,
                                "subtitle" => false,
                            ],
                            "layouts" => [
                                "shadow" => false,
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "aspectRatios" => [
                                    "landscape",
                                    "portrait",
                                    "square",
                                ],
                                "aspectRatio" => "landscape",
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "MediaList4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                            ],
                            "layouts" => [
                                "shadow" => true,
                                "sectionSize" => "l",
                                "alignment" => "left",
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "description" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Media/text",
            "description" => "Media/text Category",
            "module" => null,
            "data" => [
                "elements" => [
                    "image" => ["src" => ""],
                    "title" => "Welcome to our website",
                    "subtitle" =>
                        "Do you have more to say and show? You can do it in this section. Add pictures and a short description to show visitors more of whatever it is you want.",
                    "description" => "Add a description here.",
                    "address" => "Address",
                    "phone" => "Phone",
                    "email" => "Email",
                    "buttons" => [["title" => "Read more"]],
                ],
            ],
            "limit_per_page" => 2,
            "sections" => [
                [
                    "name" => "MediaText1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "elements" => [
                                "image" => true,
                                "subtitle" => true,
                                "title" => true,
                                "description" => false,
                                "dividerLine" => true,
                                "buttons" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                // "alignment" => "left",
                            ],
                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "MediaText2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            // "alignments" => ["left", "right"],
                            // "alignment" => "left",
                            "elements" => [
                                "image" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                                "buttons" => false,
                                "dividerLine" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                // "alignment" => "left",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "MediaText3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            // "alignments" => ["left", "right"],
                            // "alignment" => "left",
                            "elements" => [
                                "image" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                                "buttons" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                // "alignment" => "left",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "MediaText4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            // "alignments" => ["left", "right"],
                            // "alignment" => "left",
                            "elements" => [
                                "image" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                                "buttons" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                // "alignment" => "left",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "MediaText5",
                    "data" => [
                        "setting" => [
                            "layout" => 5,
                            // "alignments" => ["left", "right"],
                            // "alignment" => "left",
                            "elements" => [
                                "image" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                                "buttons" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                // "alignment" => "left",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "MediaText6",
                    "data" => [
                        "setting" => [
                            "layout" => 6,
                            // "alignments" => ["left", "right"],
                            // "alignment" => "left",
                            "elements" => [
                                "image" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                                "buttons" => false,
                                "dividerLine" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                // "alignment" => "left",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "MediaText7",
                    "data" => [
                        "setting" => [
                            "layout" => 7,
                            "elements" => [
                                "subtitle" => true,
                                "title" => true,
                                "description" => true,
                                "buttons" => false,
                                "dividerLine" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                // "alignment" => "center",
                            ]
                        ],
                        "background" => [
                            "type" => "image",
                            "image" => "15.jpg",
                        ],
                    ],
                ],
                [
                    "name" => "MediaText8",
                    "data" => [
                        "setting" => [
                            "layout" => 8,
                            // "alignment" => "left",
                            "elements" => [
                                "image" => true,
                                "title" => true,
                                "buttons" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                // "alignment" => "center",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Menu",
            "description" => "Menu Category",
            "status" => 0,
            "module" => null,
            "data" => [
                "elements" => [
                    "title" => "Menu",
                    "subtitle" => "Click here to edit your subtitle",
                    "description" =>
                        "Click here to edit your description",
                ],
                "items" => [
                    "item" => [
                        "title" => "Tom Yom Goong",
                        "subtitle" => "Add a description here",
                        "price" => "$19",
                    ],
                    "count" => 5,
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Menu1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "alignments" => ["left", "right"],
                            "alignment" => "left",
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => ["alignment" => "left"],
                            "listElements" => [
                                "title" => true,
                                "description" => true,
                                "price" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "image",
                            "image" => "15.jpg",
                        ],
                    ],
                ],
                [
                    "name" => "Menu2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "alignments" => ["left", "right"],
                            "alignment" => "left",
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "s",
                                "shadow" => true,
                            ],
                            "listElements" => [
                                "title" => true,
                                "description" => true,
                                "price" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Menu3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "columns" => [1, 2],
                            "column" => 2,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],
                            "listElements" => [
                                "title" => true,
                                "description" => true,
                                "price" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Menu4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "columns" => [1, 2, 3],
                            "column" => 2,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => ["alignment" => "left"],
                            "listElements" => [
                                "title" => true,
                                "description" => true,
                                "price" => true,
                                "dividerLine" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Menu5",
                    "data" => [
                        "setting" => [
                            "layout" => 5,
                            "columns" => [1, 2, 3],
                            "column" => 2,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => ["alignment" => "left"],
                            "listElements" => [
                                "title" => true,
                                "description" => true,
                                "price" => true,
                                "dividerLine" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Menu6",
                    "data" => [
                        "setting" => [
                            "layout" => 6,
                            "columns" => [1, 2, 3],
                            "column" => 3,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],
                            "listElements" => [
                                "title" => true,
                                "description" => true,
                                "price" => true,
                                "dividerLine" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Menu7",
                    "data" => [
                        "setting" => [
                            "layout" => 7,
                            "columns" => [1, 2, 3],
                            "column" => 3,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],
                            "listElements" => [
                                "title" => true,
                                "description" => true,
                                "price" => true,
                                "dividerLine" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Paypal",
            "description" => "Paypal Category",
            "status" => 0,
            "module" => null,
            "data" => [
                "elements" => [
                    "title" => "Product",
                    "subtitle" => "CLick here to edit your subtitle",
                    "description" => "Add a description here",
                    "button" => ["title" => "View Cart"],
                ],
                "items" => [
                    "item" => [
                        "title" => "Product 1",
                        "description" => "Product description",
                        "button" => ["title" => "Add to cart"],
                        "image" => ["src" => ""],
                    ],
                    "count" => 3,
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Paypal1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "columns" => [2, 3, 4],
                            "column" => 3,
                            "paypal" => [
                                "paypalAccount" => "",
                                "currency" => "$",
                                "cartButton" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "shadow" => true,
                            ],
                            "elements" => [
                                "description" => false,
                                "title" => true,
                                "subtitle" => true,
                            ],
                            "listElements" => [
                                "image" => true,
                                "description" => true,
                                "title" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Paypal2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "columns" => [2, 3, 4],
                            "column" => 3,
                            "paypal" => [
                                "paypalAccount" => "",
                                "currency" => "$",
                                "cartButton" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "shadow" => true,
                            ],
                            "elements" => [
                                "description" => false,
                                "title" => true,
                                "subtitle" => true,
                            ],
                            "listElements" => [
                                "image" => true,
                                "description" => true,
                                "title" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Paypal3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "columns" => [2, 3, 4],
                            "column" => 3,
                            "paypal" => [
                                "paypalAccount" => "",
                                "currency" => "$",
                                "cartButton" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "shadow" => true,
                            ],
                            "elements" => [
                                "description" => false,
                                "title" => true,
                                "subtitle" => true,
                            ],
                            "listElements" => [
                                "image" => true,
                                "description" => true,
                                "title" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "PriceList",
            "description" => "PriceList Category",
            "status" => 0,
            "module" => null,
            "data" => [
                "elements" => [
                    "title" => "Pricing",
                    "subtitle" => "Show and overview of pricing plans",
                    "description" => "Add a description here",
                ],
                "items" => [
                    "item" => [
                        "title" => "Plan",
                        "price" => "$99.95",
                        "icon" => "fa fa-home",
                        "subtitle" => "Billed monthly",
                        "description" =>
                            "Add your product features <br/> Feature1 <br/> Feature2",
                        "button" => ["title" => "Choose"],
                    ],
                    "count" => 3,
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "PriceList1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "columns" => [2, 3, 4],
                            "column" => 3,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "shadow" => true,
                            ],
                            "listElements" => [
                                "title" => true,
                                "price" => true,
                                "subtitle" => true,
                                "description" => true,
                                "buttons" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "PriceList2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "columns" => [2, 3, 4],
                            "column" => 3,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                            ],
                            "listElements" => [
                                "title" => true,
                                "price" => true,
                                "subtitle" => true,
                                "description" => true,
                                "buttons" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "PriceList3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "columns" => [2, 3, 4],
                            "column" => 3,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "shadow" => true,
                            ],
                            "listElements" => [
                                "title" => true,
                                "price" => true,
                                "subtitle" => true,
                                "description" => true,
                                "dividerLine" => true,
                                "buttons" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "PriceList4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "columns" => [2, 3, 4],
                            "column" => 3,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "shadow" => true,
                            ],
                            "listElements" => [
                                "title" => true,
                                "price" => true,
                                "subtitle" => true,
                                "description" => true,
                                "buttons" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "PriceList5",
                    "data" => [
                        "setting" => [
                            "layout" => 5,
                            "columns" => [2, 3, 4],
                            "column" => 3,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                            ],
                            "listElements" => [
                                "title" => true,
                                "price" => true,
                                "subtitle" => true,
                                "description" => true,
                                "buttons" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Product",
            "description" => "Ecommerce Category",
            "module" => "ecommerce",
            "data" => [
                "elements" => [
                    "title" => "Products",
                    "subtitle" => "Click here to edit your subtitle",
                    "description" => "Add a description here",
                ],
                "items" => [
                    "item" => [
                        "image" => ["src" => ""],
                        "title" => "Product",
                        "subtitle" => "Product Subtitle",
                        "description" => "Describe your product or give more information",
                        "button" => ["title" => "Read more"],
                    ],
                    "count" => 6,
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Product1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "columns" => [2, 3, 4, 6],
                            "column" => 4,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "product" => [
                                "category" => '',
                                "productCount" => 4,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "shadow" => true,
                                "alignment" => "left",
                                "aspectRatio" => "square",
                                "aspectRatios" => [
                                    "circle",
                                    "square",
                                    "landscape",
                                    "portrait",
                                    "original",
                                ],
                            ]

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Directory",
            "description" => "Directory Category",
            "module" => "directory",
            "data" => [
                "elements" => [
                    "title" => "Directory Listings",
                    "subtitle" => "Click here to edit your subtitle",
                    "description" => "Add a description here",
                ],
                "items" => [
                    "item" => [
                        "image" => ["src" => ""],
                        "title" => "Product",
                        "subtitle" => "Product Subtitle",
                        "description" => "Describe your product or give more information",
                        "button" => ["title" => "Read more"],
                    ],
                    "count" => 6,
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Directory1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "columns" => [2, 3, 4, 6],
                            "column" => 4,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "listing" => [
                                "category" => '',
                                "listingCount" => 4,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "shadow" => true,
                                "alignment" => "left",
                                "aspectRatio" => "square",
                                "aspectRatios" => [
                                    "circle",
                                    "square",
                                    "landscape",
                                    "portrait",
                                    "original",
                                ],
                            ]

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Appointment",
            "description" => "Appointment Category",
            "module" => "appointment",
            "data" => [
                "elements" => [
                    "title" => "Make an Appointment",
                    "subtitle" => "Let's connect today!",
                    "description" => "You can schedule an appointment here.",
                    "actionButtonText" => "Try Now",
                    "viewButtonText" => "View Detail",
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Appointment1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                                "actionButton" => true,
                                "viewButton" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "center"
                            ]
                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Progress",
            "description" => "Progress Category",
            "module" => null,
            "data" => [
                "elements" => [
                    "title" => "Meet out goals",
                    "subtitle" =>
                        "Lorem ipsum dolor sit amet, consectetur adipisicing elit.",
                    "description" =>
                        "Delectus nesciunt, quam adipisci quidem maiores molestias neque iste maxime! Rem ad voluptatibus ipsa quidem quia odio mollitia dignissimos, eius amet cum.",
                ],
                "items" => [
                    ["title" => "James Watson", "value" => 20],
                    ["title" => "Peter Johnson", "value" => 60],
                    ["title" => "Tyler Grant", "value" => 80],
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Progress1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],
                            "listElements" => ["title" => true, "value" => true],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Progress2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],
                            "listElements" => ["title" => true, "value" => true],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Progress3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],
                            "listElements" => ["title" => true, "value" => true],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Promotion",
            "description" => "Promotion Category",
            "status" => 0,
            "module" => null,
            "data" => [
                "elements" => [
                    "icon" => "fa fa-home",
                    "title" => "Get a discount on your next order",
                    "subtitle" =>
                        "Enter your e-mail address and we\'ll send you discount code",
                    "description" =>
                        "Click here to edit your description.",
                    "image" => ["src" => ""],
                    "button" => ["title" => "Get your code"],
                ],
                "promotion" => [
                    "successMessage" => [
                        "title" => "",
                        "message" => "",
                        "footNote" => "",
                    ],
                    "permissionMessage" =>
                        "By submitting your information, you are granting us permission to email you. You may unsubscribe at any time",
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Promotion1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "elements" => [
                                "icon" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                                "button" => true,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                                "shadow" => true,
                            ],
                            "promotion" => ["promotionalCode" => "DISCOUNT25"],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Promotion2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "alignment" => "left",
                            "elements" => [
                                "icon" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                                "button" => true,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                                "shadow" => true,
                            ],
                            "promotion" => ["promotionalCode" => "DISCOUNT25"],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Promotion3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "elements" => [
                                "icon" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                                "button" => true,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                                "shadow" => true,
                            ],
                            "promotion" => ["promotionalCode" => "DISCOUNT25"],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Reviews",
            "description" => "Reviews Category",
            "status" => 0,
            "module" => null,
            "data" => [
                "elements" => [
                    "title" => "Get a discount on your next order",
                    "subtitle" =>
                        "Enter your e-mail address and we\'ll send you discount code",
                    "description" =>
                        "Click here to edit your description.",
                ],
                "items" => [
                    [
                        "image" => ["src" => ""],
                        "title" => "Frances McCopy",
                        "description" =>
                            "You can edit text on your website by double clicking on a text box on your website. Alternatively, when you select a text box a settings menu will appear",
                        "review" => 3,
                        "date" => "12-07-2021",
                        "button" => ["title" => "More"],
                    ],
                    [
                        "image" => ["src" => ""],
                        "title" => "Avery woodard",
                        "description" =>
                            "You can edit text on your website by double clicking on a text box on your website. Alternatively, when you select a text box a settings menu will appear",
                        "review" => 3,
                        "date" => "12-07-2021",
                        "button" => ["title" => "More"],
                    ],
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Review1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "column" => 2,
                            "columns" => [1, 2, 3],
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "description" => false,
                                "buttons" => true,
                                "date" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Review2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "column" => 2,
                            "columns" => [1, 2, 3],
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                            ],
                            "layouts" => [
                                "shape" => "circle",
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "description" => false,
                                "buttons" => true,
                                "date" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Review3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "column" => 2,
                            "columns" => [1, 2, 3],
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                            ],
                            "layouts" => [
                                "shape" => "circle",
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "description" => false,
                                "buttons" => true,
                                "date" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Review4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "column" => 2,
                            "columns" => [1, 2, 3],
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                            ],
                            "layouts" => [
                                "shape" => "circle",
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "description" => false,
                                "buttons" => true,
                                "date" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Social",
            "description" => "Social Category",
            "module" => null,
            "data" => [
                "elements" => [
                    "title" => "Follow Us",
                    "subtitle" => "Add a subtitle here.",
                    "description" => "Add a description here.",
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Social1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Social2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Social3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                            ],
                            "layouts" => [
                                "alignment" => "left",
                                "sectionSize" => "l",
                                "shape" => "circle",
                            ],

                        ],
                        "background" => [
                            "type" => "image",
                            "image" => "8.jpg",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Statistics",
            "description" => "Statistics Category",
            "module" => null,
            "data" => [
                "elements" => [
                    "title" => "Statistics",
                    "subtitle" =>
                        "Show off why visitors should choose you. What sets you apart from others or what would you like to show your visitors?",
                    "description" => "Add a description here",
                ],
                "items" => [
                    ["subtitle" => "Michelin Stars", "value" => "3"],
                    ["subtitle" => "Amazing Dishes", "value" => "50+"],
                    [
                        "subtitle" => "Vegetarian Dishes",
                        "value" => "15+",
                    ],
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Statistics1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "column" => 6,
                            "columns" => [2, 3, 4, 6],
                            "elements" => [
                                "title" => true,
                                "description" => false,
                                "subtitle" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "shadow" => true,
                            ],
                            "listElements" => [
                                "value" => true,
                                "subtitle" => true,
                                "line" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Statistics2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "column" => 3,
                            "columns" => [2, 3, 4],
                            "elements" => [
                                "title" => true,
                                "description" => false,
                                "subtitle" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "shadow" => true,
                            ],
                            "listElements" => [
                                "value" => true,
                                "subtitle" => true,
                                "line" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Statistics3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "column" => 3,
                            "columns" => [2, 3, 4, 6],
                            "elements" => [
                                "title" => true,
                                "description" => false,
                                "subtitle" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "center",
                            ],
                            "listElements" => ["value" => true, "subtitle" => true],

                        ],
                        "background" => [
                            "type" => "image",
                            "image" => "5.jpg",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Subscribe",
            "description" => "Subscribe Category",
            "status" => 0,
            "module" => null,
            "data" => [
                "elements" => [
                    "icon" => "fa fa-home",
                    "title" => "Subscribe",
                    "subtitle" =>
                        "Sign up to our newsletter and stay up to date",
                    "description" =>
                        "Click here to edit your description",
                    "button" => ["title" => "subscribe"],
                    "image" => ["src" => ""],
                ],
                "subscribe" => [
                    "formAddress" => "",
                    "successMessage" => [
                        "title" => "Subscribed!",
                        "message" =>
                            "Thank you for subscribing to our newsletter.",
                    ],
                    "permissionMessage" =>
                        "By submitting your information, you are granting us permission to email you. You may unsubscribe at any time",
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Subscribe1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "elements" => [
                                "icon" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "form" => ["contactList" => "all"],
                            "layouts" => ["sectionSize" => "l", "shadow" => false],
                            "popover" => [
                                "popover" => false,
                                "displayPopover" => 0,
                                "timing" => 0,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Subscribe2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "alignment" => "left",
                            "elements" => [
                                "icon" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "form" => ["contactList" => "all"],
                            "layouts" => ["sectionSize" => "l"],
                            "popover" => [
                                "popover" => false,
                                "displayPopover" => 0,
                                "timing" => 0,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Subscribe3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "elements" => [
                                "icon" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "form" => ["contactList" => "all"],
                            "layouts" => ["sectionSize" => "l", "shadow" => false],
                            "popover" => [
                                "popover" => false,
                                "displayPopover" => 0,
                                "timing" => 0,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Subscribe4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "elements" => [
                                "icon" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "form" => ["contactList" => "all"],
                            "layouts" => ["sectionSize" => "l", "shadow" => false],
                            "popover" => [
                                "popover" => false,
                                "displayPopover" => 0,
                                "timing" => 0,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Subscribe5",
                    "data" => [
                        "setting" => [
                            "layout" => 5,
                            "elements" => [
                                "icon" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "form" => ["contactList" => "all"],
                            "layouts" => ["sectionSize" => "l", "shadow" => false],
                            "popover" => [
                                "popover" => false,
                                "displayPopover" => 0,
                                "timing" => 0,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Team",
            "description" => "Team Category",
            "module" => null,
            "data" => [
                "elements" => [
                    "title" => "Introduce your team",
                    "subtitle" =>
                        "Do you work with an awesome team? Of course you want to introduce them to your visitors.",
                    "description" => "Add a description here.",
                ],
                "items" => [
                    "item" => [
                        "image" => ["src" => "19.jpg"],
                        "title" => "Team member",
                        "subtitle" => "Job title or function",
                        "description" =>
                            "Write something about this member of your team to introduce them to your visitors.",
                    ],
                    "count" => 3,
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Team1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "column" => 2,
                            "columns" => [1, 2, 3],
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "shadow" => true,
                                "alignment" => "left",
                                "aspectRatio" => "filled",
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "subtitle" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Team2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "alignment" => "alternate",
                            "alignments" => ["left", "alternate", "right"],
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "shadow" => true,
                                "alignment" => "left",
                                "aspectRatio" => "square",
                                "aspectRatios" => [
                                    "circle",
                                    "square",
                                    "landscape",
                                    "portrait",
                                    "original",
                                ],
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                                "line" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Team3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "column" => 3,
                            "columns" => [2, 3, 4],
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Team4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "center",
                                "aspectRatios" => [
                                    "circle",
                                    "square",
                                    "landscape",
                                    "portrait",
                                    "original",
                                ],
                                "aspectRatio" => "circle",
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "subtitle" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Team5",
                    "data" => [
                        "setting" => [
                            "layout" => 5,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "center",
                                "aspectRatios" => [
                                    "circle",
                                    "square",
                                    "landscape",
                                    "portrait",
                                    "original",
                                ],
                                "aspectRatio" => "circle",
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "description" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Testimonials",
            "description" => "Testimonials Category",
            "module" => null,
            "status" => 0,
            "data" => [
                "elements" => [
                    "title" => "Testimonials",
                    "subtitle" => "What our customers write about us",
                    "description" =>
                        "You can edit text on your website by clicking on a text box on your website. If you have any questions, please visit our Support Center where we have lots of helpful articles that will assist you in creating the website of your dreams!",
                ],
                "items" => [
                    [
                        "icon" => "fa fa-home",
                        "description" =>
                            "You can edit text on your website by double clicking on a text box on your website. Alternatively, when you select a text box a settings menu will appear.",
                        "image" => ["src" => "7.jpg"],
                        "author" => "Lorena Watson",
                        "authorTitle" => "Job title",
                    ],
                    [
                        "icon" => "fa fa-home",
                        "description" =>
                            "You can edit text on your website by double clicking on a text box on your website. Alternatively, when you select a text box a settings menu will appear.",
                        "image" => ["src" => "7.jpg"],
                        "author" => "Emma Brown",
                        "authorTitle" => "Job title",
                    ],
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Testimonial1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "column" => 2,
                            "columns" => [1, 2, 3],
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "shape" => "circle",
                                "alignment" => "left",
                            ],
                            "listElements" => [
                                "icon" => true,
                                "description" => true,
                                "image" => true,
                                "author" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Testimonial2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "column" => 2,
                            "columns" => [1, 2],
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "shape" => "circle",
                                "alignment" => "left",
                            ],
                            "listElements" => [
                                "icon" => true,
                                "description" => true,
                                "image" => true,
                                "line" => true,
                                "author" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Testimonial3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "column" => 2,
                            "columns" => [1, 2],
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "shape" => "circle",
                                "alignment" => "left",
                            ],
                            "listElements" => [
                                "icon" => true,
                                "description" => true,
                                "image" => true,
                                "author" => true,
                                "authorTitle" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Testimonial4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "column" => 2,
                            "columns" => [2, 3, 4],
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "shadow" => true,
                                "alignment" => "left",
                            ],
                            "listElements" => [
                                "icon" => true,
                                "description" => true,
                                "image" => true,
                                "author" => true,
                                "authorTitle" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Testimonial5",
                    "data" => [
                        "setting" => [
                            "layout" => 5,
                            "column" => 2,
                            "columns" => [2, 3],
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "shape" => true,
                                "shadow" => true,
                                "alignment" => "left",
                            ],
                            "listElements" => [
                                "icon" => true,
                                "description" => true,
                                "image" => true,
                                "author" => true,
                                "authorTitle" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Timeline",
            "description" => "Timeline Category",
            "module" => null,
            "data" => [
                "elements" => [
                    "title" => "Our History",
                    "subtitle" => "Write your subtitle here",
                    "description" => "Things that definitely happened",
                ],
                "items" => [
                    "item" => [
                        "image" => ["src" => ""],
                        "title" => "Starting off",
                        "date" => "2018",
                        "description" => "Add a description here",
                    ],
                    "count" => 3,
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Timeline1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "alignment" => "alternate",
                            "alignments" => ["left", "alternate", "right"],
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "center",
                            ],
                            "listElements" => [
                                "title" => true,
                                "date" => true,
                                "description" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Timeline2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "alignment" => "alternate",
                            "alignments" => ["left", "alternate", "right"],
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "alignment" => "center",
                                "sectionSize" => "l",
                                "shape" => "circle",
                                "shapes" => ["circle", "square"],
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "date" => true,
                                "description" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Timeline3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "alignment" => "right",
                            "alignments" => ["left", "alternate", "right"],
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "center",
                                "shape" => "circle",
                                "shapes" => ["circle", "square", "hexagon"],
                            ],
                            "listElements" => [
                                "date" => true,
                                "title" => true,
                                "description" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Portfolio",
            "description" => "Portfolio Category",
            "module" => "portfolio",
            "data" => [
                "elements" => [
                  "portfolioLink" => "www.bizinzbox.com",
                ],
                "items" => [
                    "item" => [
                        "image" => ["src" => ""],
                    ],
                    "count" => 16,
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Portfolio1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "layouts" => [
                                "sectionSize" => "l",
                            ],
                            "portfolio" =>  [
                                "category"  =>  ""
                            ]
                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Portfolio2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "layouts" => [
                                "sectionSize" => "l",
                            ],
                            "portfolio" =>  [
                                "category"  =>  ""
                            ]
                        ],
                        "background" => [
                            "type" => "image",
                            "image" => "15.jpg",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "USPs",
            "description" => "USPs Category",
            "status" => 0,
            "module" => null,
            "data" => [
                "elements" => [
                    "image" => ["src" => ""],
                    "title" =>
                        "Introduce the products and/or services you offer",
                    "subtitle" =>
                        "Show your customers what you offer and shortly give a description of each service or product.",
                    "description" => "Add a description here.",
                ],
                "items" => [
                    "item" => [
                        "image" => ["src" => "6.jpg"],
                        "icon" => "fa fa-home",
                        "title" => "Product/service",
                        "subtitle" => "Subtitle",
                        "description" => "Describe the product/service",
                    ],
                    "count" => 3,
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Usp1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "column" => 3,
                            "columns" => [2, 3, 4],
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "center",
                            ],
                            "listElements" => [
                                "icon" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Usp2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "column" => 3,
                            "columns" => [2, 3, 4],
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "shadow" => true,
                                "alignment" => "center",
                            ],
                            "listElements" => [
                                "title" => true,
                                "description" => true,
                                "icon" => true,
                                "subtitle" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Usp3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "column" => 3,
                            "columns" => [2, 3, 4],
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "shadow" => true,
                                "alignment" => "center",
                            ],
                            "listElements" => [
                                "title" => true,
                                "description" => true,
                                "icon" => true,
                                "subtitle" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Usp4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "alignment" => "left",
                            "elements" => [
                                "image" => true,
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "shadow" => true,
                                "sectionSize" => "l",
                                "aspectRatio" => "landscape",
                                "alignment" => "center",
                            ],
                            "listElements" => [
                                "icon" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Usp5",
                    "data" => [
                        "setting" => [
                            "layout" => 5,
                            "column" => 3,
                            "columns" => [2, 3, 4],
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "center",
                            ],
                            "listElements" => [
                                "icon" => true,
                                "title" => true,
                                "subtitle" => true,
                                "description" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Video",
            "description" => "Video Category",
            "module" => null,
            "data" => [
                "elements" => [
                    "title" => "Welcome to our website",
                    "description" => "Add a description here.",
                    "buttons" => [["title" => "More"]],
                    "subtitle" => "Learn more about what we do.",
                ],
                "video" => [
                    "source" =>
                        "https://www.youtube.com/watch?v=LXb3EKWsInQ",
                    "setting" => [
                        "autoPlay" => false,
                        "loop" => false,
                        "controls" => true,
                    ],
                ],
            ],
            "limit_per_page" => 4,
            "sections" => [
                [
                    "name" => "Video1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "alignment" => "right",
                            "elements" => [
                                "title" => true,
                                "description" => true,
                                "buttons" => true,
                                "dividerLine" => false,
                                "subtitle" => false,
                            ],
                            "layouts" => ["alignment" => "left"],
                            "video" => [
                                "source" => 'https://www.youtube.com/watch?v=LXb3EKWsInQ',
                                "autoplay" => false,
                                "loop" => false,
                                "mute" => false,
                                "controls" => true,
                                "isYoutube" => true,
                            ],
                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Video2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "elements" => [
                                "title" => true,
                                "description" => true,
                                "buttons" => true,
                                "subtitle" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                            ],
                            "video" => [
                                "source" => 'https://www.youtube.com/watch?v=LXb3EKWsInQ',
                                "autoplay" => false,
                                "loop" => false,
                                "mute" => false,
                                "controls" => true,
                                "isYoutube" => true,
                            ],
                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Video3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "alignment" => "left",
                            "elements" => [
                                "title" => true,
                                "description" => true,
                                "buttons" => true,
                                "subtitle" => false,
                            ],
                            "layouts" => [
                                // "shadow" => true,
                                "sectionSize" => "l",
                                "alignment" => "left",
                            ],
                            "video" => [
                                "source" => 'https://www.youtube.com/watch?v=LXb3EKWsInQ',
                                "autoplay" => false,
                                "loop" => false,
                                "mute" => false,
                                "controls" => true,
                                "isYoutube" => true,
                            ],
                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Video4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "alignment" => "left",
                            "elements" => [
                                "title" => true,
                                "description" => true,
                                "buttons" => true,
                                "subtitle" => true,
                            ],
                            "layouts" => [
                                // "shadow" => true,
                                "alignment" => "left",
                                "sectionSize" => "l",
                            ],
                            "video" => [
                                "source" => 'https://www.youtube.com/watch?v=LXb3EKWsInQ',
                                "autoplay" => false,
                                "loop" => false,
                                "mute" => false,
                                "controls" => true,
                                "isYoutube" => true,
                            ],
                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Event",
            "description" => "Event Category",
            "status" => 0,
            "module" => null,
            "data" => [
                "elements" => [
                    "title" => "Events",
                    "subtitle" => "subtitle",
                    "description" => "Add a description here.",
                ],
                "items" => [
                    "item" => [
                        "image" => ["src" => ""],
                        "date" => "",
                        "venue" => "Paradise Rock Club",
                        "location" => "Boston, MA",
                        "button" => ["title" => "Tickets"],
                    ],
                    "count" => 3,
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Event1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => true,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "aspectRatio" => "original",
                            ],
                            "listElements" => [
                                "date" => true,
                                "venue" => true,
                                "location" => true,
                                "image" => false,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Event2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "aspectRatio" => "landscape",
                            ],
                            "listElements" => [
                                "date" => true,
                                "venue" => true,
                                "location" => true,
                                "image" => true,
                                "dividerLine" => true,
                                "buttons" => false,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Event3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "columns" => [3, 4],
                            "column" => 4,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "aspectRatio" => "landscape",
                                "shadow" => true,
                            ],
                            "listElements" => [
                                "date" => true,
                                "venue" => true,
                                "location" => true,
                                "image" => true,
                                "buttons" => false,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Event4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "m",
                                "alignment" => "left",
                                "fullWidth" => true,
                            ],
                            "listElements" => [
                                "date" => true,
                                "venue" => true,
                                "location" => true,
                                "buttons" => false,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Event5",
                    "data" => [
                        "setting" => [
                            "layout" => 5,
                            "columns" => [1, 2],
                            "column" => 1,
                            "elements" => [
                                "title" => true,
                                "subtitle" => false,
                                "description" => false,
                            ],
                            "layouts" => [
                                "sectionSize" => "m",
                                "alignment" => "left",
                            ],
                            "listElements" => [
                                "date" => true,
                                "venue" => true,
                                "location" => true,
                                "buttons" => false,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Blog",
            "description" => "Blog Category",
            "module" => "simple_blog",
            "data" => [
                "elements" => [
                    "title" => "My Blog Items",
                    "subtitle" => "Click here to edit your subtitle",
                    "description" => "Add a description here",
                    "button" => ["title" => "Read More"],
                ],
                "items" => [
                    "item" => [
                        "image" => ["src" => ""],
                        "title" => "Blog Item",
                        "description" =>
                            "Describe your product or give more information",
                        "button" => ["title" => "Read more"],
                    ],
                    "count" => 5,
                ],
            ],
            "limit_per_page" => 3,
            "sections" => [
                [
                    "name" => "Blog1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "columns" => [2, 3, 4],
                            "column" => 3,
                            "columnHide" => [2],
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "blog" => [
                                "category" => '',
                                "blogCount" => 3,
                                "descriptionLength" => 150,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "shadow" => false,
                                "alignment" => "left",
                                "aspectRatio" => "square",
                                "aspectRatios" => [
                                    "circle",
                                    "square",
                                    "landscape",
                                    "portrait",
                                    "original",
                                ],
                            ],
                            "listElements" => [
                                "title" => true,
                                "description" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Blog2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "columns" => [1, 2],
                            "column" => 1,
                            "columnHide" => [2],
                            "blog" => [
                                "category" => '',
                                "blogCount" => 3,
                                "descriptionLength" => 150,
                            ],
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "layouts" => [
                                "category" => '',
                                "blogCount" => 3,
                                "sectionSize" => "l",
                                "shadow" => false,
                                "alignment" => "left",
                                "aspectRatio" => "square",
                                "aspectRatios" => [
                                    "circle",
                                    "square",
                                    "landscape",
                                    "portrait",
                                    "original",
                                ],
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "description" => true,
                                "lines" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Blog3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "blog" => [
                                "category" => '',
                                "blogCount" => 3,
                                "descriptionLength" => 150,
                            ],
                            "layouts" => [
                                "category" => '',
                                "blogCount" => 5,
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "aspectRatio" => "square",
                                "aspectRatios" => [
                                    "circle",
                                    "square",
                                    "landscape",
                                    "portrait",
                                    "original",
                                ],
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "description" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Blog4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "blog" => [
                                "category" => '',
                                "blogCount" => 3,
                                "descriptionLength" => 150,
                            ],
                            "layouts" => [
                                "category" => '',
                                "blogCount" => 3,
                                "sectionSize" => "s",
                                "alignment" => "left",
                            ],
                            "listElements" => [
                                "title" => true,
                                "description" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "image",
                            "image" => "15.jpg",
                        ],
                    ],
                ],
                [
                    "name" => "Blog5",
                    "data" => [
                        "setting" => [
                            "layout" => 5,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "blog" => [
                                "category" => '',
                                "blogCount" => 3,
                                "descriptionLength" => 150,
                            ],
                            "layouts" => [
                                "category" => '',
                                "blogCount" => 5,
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "aspectRatio" => "square",
                                "aspectRatios" => [
                                    "circle",
                                    "square",
                                    "landscape",
                                    "portrait",
                                    "original",
                                ],
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "description" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Blog6",
                    "data" => [
                        "setting" => [
                            "layout" => 6,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "blog" => [
                                "category" => '',
                                "blogCount" => 3,
                                "descriptionLength" => 150,
                            ],
                            "layouts" => [
                                "category" => '',
                                "blogCount" => 4,
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "aspectRatio" => "square",
                                "aspectRatios" => [
                                    "circle",
                                    "square",
                                    "landscape",
                                    "portrait",
                                    "original",
                                ],
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "description" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Blog7",
                    "data" => [
                        "setting" => [
                            "layout" => 7,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "blog" => [
                                "category" => '',
                                "blogCount" => 5,
                                "descriptionLength" => 150,
                            ],
                            "layouts" => [
                                "category" => '',
                                "blogCount" => 4,
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "aspectRatio" => "square",
                                "aspectRatios" => [
                                    "circle",
                                    "square",
                                    "landscape",
                                    "portrait",
                                    "original",
                                ],
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "description" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Blog8",
                    "data" => [
                        "setting" => [
                            "layout" => 8,
                            "elements" => [
                                "title" => true,
                                "subtitle" => true,
                                "description" => false,
                            ],
                            "blog" => [
                                "category" => '',
                                "blogCount" => 4,
                                "descriptionLength" => 150,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                                "alignment" => "left",
                                "aspectRatio" => "square",
                                "aspectRatios" => [
                                    "circle",
                                    "square",
                                    "landscape",
                                    "portrait",
                                    "original",
                                ],
                            ],
                            "listElements" => [
                                "image" => true,
                                "title" => true,
                                "description" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Blog9",
                    "data" => [
                        "setting" => [
                            "layout" => 9,
                            "blog" => [
                                "category" => '',
                                "blogCount" => 4,
                                "descriptionLength" => 150,
                            ],
                            "layouts" => [
                                "sectionSize" => "l",
                            ],
                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Booking",
            "description" => "Booking Category",
            "module" => null,
            "status" => 0,
            "limit_per_page" => 1,
            "sections" => [],
        ],
        [
            "name" => "Store",
            "description" => "Store Category",
            "status" => 0,
            "module" => null,
            "limit_per_page" => 1,
            "sections" => [],
        ],
        [
            "name" => "Header",
            "description" => "Header Category",
            "module" => null,
            "order" => 1,
            "data" => [
                "elements" => [
                    "siteTitle" => "Site Title",
                    "headerButton" => ["title" => "Call"],
                    "subtitle" => "Subtitle",
                    "description" => "description",
                ],
                'navigations' => [
                    [
                        'name' => 'Home',
                        'link' => [
                            'type' => 'page',
                            'page' => '/',
                        ],
                        'children' => [],
                    ],
                    [
                        'name' => 'Service',
                        'children' => [
                            [
                                'name' => 'Shop',
                                'image' => [
                                    'src' => 'https://media.istockphoto.com/photos/friends-shopping-for-perfect-dres-picture-id1337300784',
                                ],
                                'children' => [],
                            ],
                            [
                                'name' => 'Consign',
                                'children' => [],
                            ],
                            [
                                'name' => 'Buy',
                                'children' => [],
                            ],
                        ],
                    ],
                    [
                        'name' => 'About Us',
                        'children' => [],
                    ],
                    [
                        'name' => 'Clothing',
                        'children' => [
                            [
                                'name' => 'Boys',
                                'children' => [],
                            ],
                            [
                                'name' => 'Girls',
                                'children' => [],
                            ],
                            [
                                'name' => 'Babies',
                                'children' => [
                                    [
                                        'name' => 'Hats',
                                        'children' => [],
                                    ],
                                    [
                                        'name' => 'Shoes',
                                        'children' => [],
                                    ],
                                    [
                                        'name' => 'Socks',
                                        'children' => [],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Contact',
                        'children' => [],
                    ],
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Header1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "elements" => [
                                "siteTitle" => false,
                                "registerButton" => true,
                                "loginButton" => true
                            ],
                            "header" => [
                                "fixedNavigationBar" => true,
                                "scaleTextToWidth" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Header2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "elements" => [
                                "siteTitle" => false,
                                "registerButton" => true,
                                "loginButton" => true
                            ],
                            "header" => [
                                "fixedNavigationBar" => true,
                                "scaleTextToWidth" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Header3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "elements" => [
                                "siteTitle" => false,
                                "registerButton" => true,
                                "loginButton" => true
                            ],
                            "header" => [
                                "fixedNavigationBar" => true,
                                "scaleTextToWidth" => true,
                            ],
                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Header4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "elements" => [
                                "siteTitle" => false,
                                "registerButton" => true,
                                "loginButton" => true
                            ],
                            "header" => [
                                "fixedNavigationBar" => true,
                                "scaleTextToWidth" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Header5",
                    "data" => [
                        "setting" => [
                            "layout" => 5,
                            "elements" => [
                                "logo" => true,
                                "registerButton" => true,
                                "loginButton" => true
                            ],
                            "header" => [
                                "fixedNavigationBar" => true,
                                "scaleTextToWidth" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Header6",
                    "data" => [
                        "setting" => [
                            "layout" => 6,
                            "elements" => [
                                "logo" => true,
                                "siteTitle" => false,
                                "topBar" => true,
                                "registerButton" => true,
                                "loginButton" => true
                            ],
                            "header" => [
                                "fixedNavigationBar" => true,
                                "scaleTextToWidth" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Header7",
                    "data" => [
                        "setting" => [
                            "layout" => 7,
                            "elements" => [
                                "registerButton" => true,
                                "loginButton" => true
                            ],
                            "header" => [
                                "fixedNavigationBar" => true,
                                "scaleTextToWidth" => true,
                            ],

                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
                [
                    "name" => "Header8",
                    'status' => false,
                    "data" => [
                        "setting" => [
                            "layout" => 8,
                            'elements' => [
                                'topBar' => true,
                                'logo' => true,
                                'title' => true,
                                "registerButton" => true,
                                "loginButton" => true
                            ],
                        ],
                        "background" => [
                            "type" => "color",
                            "size" => "auto",
                        ],
                    ],
                ],
            ],
        ],
        [
            "name" => "Footer",
            "description" => "Footer Category",
            "module" => null,
            "order" => 2,
            "data" => [
                "elements" => [
                    "description" => "Add a description here",
                    "title" => "Add a title here",
                    "subtitle" => "Click here to edit your subtitle",
                    "copyrightMessage" => "copyright text here",
                ],
                "items" => [
                    [
                        "title" => "Product",
                        "links" => [
                            [
                                'label' => 'Features',
                            ],
                            [
                                'label' => 'Team',
                            ],
                            [
                                'label' => 'Security',
                            ],
                        ],
                    ],
                    [
                        "title" => "Company",
                        "links" => [
                            [
                                'label' => 'About',
                            ],
                            [
                                'label' => 'Blog',
                            ],
                            [
                                'label' => 'Careers',
                            ],
                            [
                                'label' => 'Shop',
                            ],
                        ],
                    ],
                ],
            ],
            "limit_per_page" => 1,
            "sections" => [
                [
                    "name" => "Footer1",
                    "data" => [
                        "setting" => [
                            "layout" => 1,
                            "elements" => [
                                "siteTitle" => false,
                                "socialIcons" => true,
                                "description" => true,
                                "phoneNumber" => false,
                                "eMail" => true,
                                "copyrightMessage" => false,
                                "address" => true,
                                "pagesMenu" => true,
                                "dividerLine" => true,
                                "contact" => true,
                            ],
                            "businessInformation" => ["location" => ""],

                        ],
                        "background" => [
                            "type" => "color",
                            "color" => "#00000000",
                        ],
                    ],
                ],
                [
                    "name" => "Footer2",
                    "data" => [
                        "setting" => [
                            "layout" => 2,
                            "elements" => [
                                "siteTitle" => false,
                                "subtitle" => true,
                                "phoneNumber" => false,
                                "socialIcons" => true,
                                "copyrightMessage" => false,
                                "pagesMenu" => true,
                                "dividerLine" => true,
                            ],
                            "businessInformation" => ["location" => ""],

                        ],
                        "background" => [
                            "type" => "image",
                            "image" => "25.jpg",
                        ],
                    ],
                ],
                [
                    "name" => "Footer3",
                    "data" => [
                        "setting" => [
                            "layout" => 3,
                            "elements" => [
                                "description" => true,
                                "siteTitle" => false,
                                "socialIcons" => true,
                                "phoneNumber" => false,
                                "eMail" => true,
                                "copyrightMessage" => false,
                                "address" => true,
                                "pagesMenu" => true,
                                "dividerLine" => true,
                            ],
                            "businessInformation" => ["location" => ""],

                        ],
                        "background" => [
                            "type" => "color",
                            "color" => "#00000000",
                        ],
                    ],
                ],
                [
                    "name" => "Footer4",
                    "data" => [
                        "setting" => [
                            "layout" => 4,
                            "elements" => [
                                "siteTitle" => false,
                                "phoneNumber" => false,
                                "eMail" => true,
                                "description" => true,
                                "socialIcons" => true,
                                "copyrightMessage" => false,
                                "address" => true,
                                "pagesMenu" => true,
                                "dividerLine" => true,
                                "contact" => true,
                            ],
                            "businessInformation" => ["location" => ""],

                        ],
                        "background" => [
                            "type" => "color",
                            "color" => "#00000000",
                        ],
                    ],
                ],
                [
                    "name" => "Footer5",
                    "data" => [
                        "setting" => [
                            "layout" => 5,
                            "elements" => [
                                "phoneNumber" => false,
                                "eMail" => true,
                                "socialIcons" => true,
                                "description" => true,
                                "siteTitle" => false,
                                "copyrightMessage" => false,
                                "address" => true,
                                "pagesMenu" => true,
                                "dividerLine" => true,
                            ],
                            "businessInformation" => ["location" => ""],

                        ],
                        "background" => [
                            "type" => "color",
                            "color" => "#00000000",
                        ],
                    ],
                ],
                [
                    "name" => "Footer6",
                    "data" => [
                        "setting" => [
                            "layout" => 6,
                            "elements" => [
                                "socialIcons" => true,
                                "description" => true,
                                "copyrightMessage" => false,
                            ],
                        ],
                        "background" => [
                            "type" => "color",
                            "color" => "#00000000",
                        ],
                    ],
                ],
                [
                    "name" => "Footer7",
                    "data" => [
                        "setting" => [
                            "layout" => 7,
                        ],
                        "background" => [
                            "type" => "color",
                            "color" => "#00000000",
                        ],
                    ],
                ],
                [
                    "name" => "Footer8",
                    "data" => [
                        "setting" => [
                            "layout" => 8,
                        ],
                        "background" => [
                            "type" => "color",
                            "color" => "#ED5494",
                        ],
                    ],
                ],
                [
                    "name" => "Footer9",
                    "data" => [
                        "setting" => [
                            "layout" => 9,
                        ],
                        "background" => [
                            "type" => "color",
                            "color" => "#00000000",
                        ],
                    ],
                ],
                [
                    "name" => "Footer10",
                    "data" => [
                        "setting" => [
                            "layout" => 10,
                        ],
                        "background" => [
                            "type" => "color",
                            "color" => "#00000000",
                        ],
                    ],
                ],
            ],
        ],
    ],
];
