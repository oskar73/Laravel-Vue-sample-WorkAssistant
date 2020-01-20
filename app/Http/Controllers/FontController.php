<?php

namespace App\Http\Controllers;

use App\Repositories\FontRepository;

class FontController extends Controller
{
    /**
     * @var FontRepository
     */
    protected $fonts;

    /**
     * LogoController constructor.
     *
     * @param FontRepository $fonts
     */
    public function __construct(FontRepository $fonts)
    {
        $this->fonts = $fonts;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFonts()
    {
        //        return response()->json($this->fonts->all(['name']));
        return response()->json($this->fonts->model::orderBy('name', 'asc')->select('name')->get());
    }
}
