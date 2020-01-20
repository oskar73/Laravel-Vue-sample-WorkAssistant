<?php

namespace App\Jobs;

use App\Enums\StorageName;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SiteMapGenerateJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->generateSiteMap();
    }

    public static function removeRootUrl($url): string
    {
        $root = url('/');
        $result = str_replace($root, '', $url);
        if ($result == '') {
            return '/';
        } else {
            return $result;
        }
    }

    public function generateSiteMap()
    {
        $sitemap = Sitemap::create();
        $date = Carbon::now();
        $frequency = Url::CHANGE_FREQUENCY_WEEKLY;
        $mainUrls = [
            '/' => 1,
            '/blog' => 0.8,
            '/package' => 0.8,
            '/modules' => 0.6,
            '/blogAds' => 0.6,
            '/templates' => 0.6,
        ];
        foreach ($mainUrls as $url => $point) {
            $sitemap->add(
                Url::create($url)
                    ->setPriority($point)
                    ->setLastModificationDate($date)
                    ->setChangeFrequency($frequency)
            );
        }

        // Auth routes
        $sitemap->add(Url::create('https://accounts.bizinabox.com/login')->setPriority(0.6)->setLastModificationDate($date)->setChangeFrequency($frequency));
        $sitemap->add(Url::create('https://accounts.bizinabox.com/register')->setPriority(0.6)->setLastModificationDate($date)->setChangeFrequency($frequency));

        //blog category
        $blogCategories = BlogCategory::where("status", 1)->get();
        foreach ($blogCategories as $blogCategory) {
            $sitemap->add(
                Url::create(
                    $this->removeRootUrl(
                        route('blog.category', $blogCategory->slug)
                    )
                )
                    ->setPriority(0.6)
                    ->setLastModificationDate($date)
                    ->setChangeFrequency($frequency)
            );
        }

        //blog Tags
        $blogTags = BlogTag::where("status", 1)->get();
        foreach ($blogTags as $blogTag) {
            $sitemap->add(
                Url::create(
                    $this->removeRootUrl(
                        route('blog.tag', $blogTag->slug)
                    )
                )
                    ->setPriority(0.6)
                    ->setLastModificationDate($date)
                    ->setChangeFrequency($frequency)
            );
        }

        //blog Posts
        $blogPosts = BlogPost::frontVisible()->get();
        foreach ($blogPosts as $blogPost) {
            $sitemap->add(
                Url::create(
                    $this->removeRootUrl(
                        route('blog.detail', $blogPost->slug)
                    )
                )
                    ->setPriority(0.4)
                    ->setLastModificationDate($date)
                    ->setChangeFrequency($frequency)
            );
        }

        $sitemap->writeToDisk(config('app.env') === 'local' ? 'local' : StorageName::BIZINABOX, 'static/sitemap.xml');

        $google_services = option("google_services", []);

        $google_services["sitemap_updated"] = now()->toDateString();

        option(["google_services" => $google_services]);
    }
}
