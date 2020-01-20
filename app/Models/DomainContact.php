<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainContact extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $table = 'domain_contacts';
}
