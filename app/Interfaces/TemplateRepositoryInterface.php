<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface TemplateRepositoryInterface
{
    public function getCategories();
    public function updateOne(Request $request, $id);
}
