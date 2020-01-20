<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class ModuleMedia extends BaseMedia
{
    use HasFactory;

    protected $connection = 'mysql2';
}
