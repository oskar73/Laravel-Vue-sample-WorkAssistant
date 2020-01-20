<?php

return [
    'use_default_config' => env('BROWSER_SHOT_USE_DEFAULT_CONFIG', true),
    /*
    |--------------------------------------------------------------------------
    | Configurations for node
    |--------------------------------------------------------------------------
    |
    */
    'npm_bin_path' => env('NPM_BIN_PATH', '/usr/bin/npm'),
    'node_bin_path' => env('NODE_BIN_PATH', '/usr/bin/node'),
    'node_module_path' => env('NODE_MODULE_PATH', '/var/task/node_modules'),
    'chromium_path' => env('CHROMIUM_PATH', '/usr/bin/chromium-browser'),
    'bin_path' => env('BIN_PATH', '/var/task/vendor/spatie/browsershot/bin/browser.js'),
    'chrome_arguments' => [
        'allow-running-insecure-content',
        'autoplay-policy' => 'user-gesture-required',
        'disable-component-update',
        'disable-domain-reliability',
        'disable-features' => 'AudioServiceOutOfProcess,IsolateOrigins,site-per-process',
        'disable-print-preview',
        'disable-setuid-sandbox',
        'disable-site-isolation-trials',
        'disable-speech-api',
        'disable-web-security',
        'disk-cache-size' => 33554432,
        'enable-features' => 'SharedArrayBuffer',
        'hide-scrollbars',
        'ignore-gpu-blocklist',
        'in-process-gpu',
        'mute-audio',
        'no-default-browser-check',
        'no-pings',
        'no-sandbox',
        'no-zygote',
        'use-gl' => 'swiftshader',
        'window-size' => '1920,1080',
        'single-process',
        'disable-2d-canvas-clip-aa',
        'disable-accelerated-2d-canvas',
        'disable-breakpad',
        'disable-canvas-aa',
        'disable-desktop-notifications',
        'disable-extensions',
        'disable-gl-drawing-for-tests',
        'disable-permissions-api',
        'disable-sync',
        'no-first-run',
    ],
];
