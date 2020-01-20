<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GraphicDesign\Graphic;
use App\Models\GraphicDesign\GraphicDesign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SectionBuilderApi extends Controller
{
    public function loadStockFavicons(Request $request): JsonResponse
    {
        try {
            $faviconGraphic = Graphic::findBySlug('favicon');
            $page = $request->get('page') ?? 0;
            $favicons = GraphicDesign::where('graphic_id', $faviconGraphic->id)->offset($page * 10)->limit(10)->get();

            return $this->jsonSuccess(['stockFavicons' => $favicons]);
        } catch (\Exception $exception) {
            return $this->jsonExceptionError($exception);
        }
    }

    public function loadStockLogos(Request $request): JsonResponse
    {
        try {
            $logoGraphic = Graphic::findBySlug('logo');
            $page = $request->get('page') ?? 0;
            $logos = GraphicDesign::where('graphic_id', $logoGraphic->id)->offset($page * 10)->limit(10)->get();

            return $this->jsonSuccess(['stockLogos' => $logos]);
        } catch (\Exception $exception) {
            return $this->jsonExceptionError($exception);
        }
    }

    public function loadUserFavicons(): JsonResponse
    {
        try {
            $faviconGraphic = Graphic::findBySlug('favicon');
            $user = user();
            $favicons = $user->designs()->where('graphic_id', $faviconGraphic->id)->get();

            return $this->jsonSuccess([
                'userFavicons' => $favicons,
            ]);
        } catch (\Exception $exception) {
            return $this->jsonExceptionError($exception);
        }
    }

    public function loadUserLogos(): JsonResponse
    {
        try {
            $logoGraphic = Graphic::findBySlug('logo');
            $user = user();
            $logos = $user->designs()->where('graphic_id', $logoGraphic->id)->get();

            return $this->jsonSuccess([
                'userLogos' => $logos,
            ]);
        } catch (\Exception $exception) {
            return $this->jsonExceptionError($exception);
        }
    }
}
