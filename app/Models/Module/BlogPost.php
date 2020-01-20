<?php

namespace App\Models\Module;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BlogPost extends ModuleBaseModel implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'blog_posts';

    protected $appends = ['image'];

    public function getImageAttribute(): string
    {
        return $this->getFirstMediaUrl("image");
    }
}
