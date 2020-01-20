<?php

namespace App\Integration;

use Barryvdh\Elfinder\Connector;
use Barryvdh\Elfinder\ElfinderController;
use Barryvdh\Elfinder\Session\LaravelSession;
use Illuminate\Filesystem\FilesystemAdapter;
use Session;

class CustomElfinderProvider extends ElfinderController
{
    public function showConnector()
    {
        $roots = $this->app->config->get('elfinder.roots', []);
        if (empty($roots)) {
            if (Session::get('file.storage') == 'main') {
                $dirs = (array) $this->app['config']->get('elfinder.dir', []);
                foreach ($dirs as $dir) {
                    $roots[] = [
                        'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                        'path' => public_path($dir), // path to files (REQUIRED)
                        'URL' => url($dir), // URL to files (REQUIRED)
                        'accessControl' => $this->app->config->get('elfinder.access'), // filter callback (OPTIONAL)
                        'alias' => 'Main Storage',
                        'tmbPath' => public_path($dir).'/thumbs',
                        'tmbURL' => url($dir).'/thumbs',
                    ];
                }
            }

            if (Session::get('file.storage') == 'users') {
                $disks = (array) $this->app['config']->get('elfinder.disks', []);
                foreach ($disks as $key => $root) {
                    if (is_string($root)) {
                        $key = $root;
                        $root = [];
                    }
                    $disk = app('filesystem')->disk($key);
                    if ($disk instanceof FilesystemAdapter) {
                        $defaults = [
                            'driver' => 'Flysystem',
                            'filesystem' => $disk->getDriver(),
                            'alias' => $key,
                            'path' => '/',
                        ];
                        $roots[] = array_merge($defaults, $root);
                    }
                }
            } else {
                $disks = (array) $this->app['config']->get('elfinder.disks', []);
                $websites = \App\Models\Website::where('user_id', user()->id)->get();

                foreach ($disks as $key => $root) {
                    foreach ($websites as $website) {
                        $website_id = \Str::slug($website->name);
                        if (\Storage::disk($root)->exists($website_id)) {
                            if (is_string($root)) {
                                $key = $root;
                                $root = [];
                            }
                            $disk = app('filesystem')->disk($key);
                            if ($disk instanceof FilesystemAdapter) {
                                $defaults = [
                                       'driver' => 'Flysystem',
                                       'filesystem' => $disk->getDriver(),
                                       'alias' => $website_id,
                                       'path' => $website_id,
                                   ];
                                $roots[] = array_merge($defaults, $root);
                            }
                        }
                    }
                }
            }
        }
        if (app()->bound('session.store')) {
            $sessionStore = app('session.store');
            $session = new LaravelSession($sessionStore);
        } else {
            $session = null;
        }

        $rootOptions = $this->app->config->get('elfinder.root_options', []);
        $disableds = \Session::get("disk_status", 1) == 1? []:['upload', 'paste', 'duplicate', 'extract', 'mkfile', 'mkdir'];
        $rootOptions['disabled'] = $disableds;
        foreach ($roots as $key => $root) {
            $roots[$key] = array_merge($rootOptions, $root);
        }

        $opts = $this->app->config->get('elfinder.options', []);
        $opts = array_merge($opts, ['roots' => $roots, 'session' => $session]);

        // run elFinder
        $connector = new Connector(new \elFinder($opts));
        $connector->run();

        return $connector->getResponse();
    }
}
