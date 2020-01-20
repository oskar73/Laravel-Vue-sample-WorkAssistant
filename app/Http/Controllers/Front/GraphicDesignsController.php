<?php

namespace App\Http\Controllers\Front;

use App\Enums\HttpStatusEnum;
use App\Http\Controllers\Controller;
use App\Jobs\ActualizeDesignPreview;
use App\Jobs\SendDesignPackageToClient;
use App\Models\GraphicDesign\ColorCategory;
use App\Models\GraphicDesign\Graphic;
use App\Models\GraphicDesign\GraphicCategory;
use App\Models\GraphicDesign\GraphicDesign;
use App\Models\GraphicDesign\UserDesign;
use App\Models\Palette;
use App\Models\UserPalette;
use App\Repositories\GraphicDesignRepository;
use App\Repositories\UserDesignRepository;
use App\Services\DesignService;
use Illuminate\Http\Request;

class GraphicDesignsController extends Controller
{
    public UserDesignRepository $userDesign;
    public GraphicDesignRepository $design;

    public function __construct(
        UserDesignRepository    $userDesign,
        GraphicDesignRepository $design
    )
    {
        $this->userDesign = $userDesign;
        $this->design = $design;
    }

    public function index()
    {
        if ($graphic = Graphic::first()) {
            return redirect(route('graphics.category', $graphic->slug));
        } else {
            return redirect('/');
        }
    }

    public function viewCategory($slug)
    {
        $graphic = Graphic::findBySlug($slug);
        $designs = GraphicDesign::where('graphic_id', $graphic->id)
            ->where('status', 1)
            ->select(['id', 'hash', 'preview', 'premium'])
            ->orderBy('recommend', 'desc')
            ->take(16)
            ->get()
            ->pad(16, null)
            ->toArray();

        $frontSetting = $graphic->front_settings;
        $seo = $frontSetting['seo'] ?? null;

        return view('frontend.graphic-designs.index', [
            'designs' => $designs,
            'seo' => $seo,
            'graphic' => $graphic,
            'frontSetting' => $frontSetting,
        ]);
    }

    public function viewDesignCategories($slug, Request $request)
    {
        $categorySlug = $request->get('category_slug');
        $categories = GraphicCategory::all();
        if ($categorySlug) {
            $category = GraphicCategory::findBySlug($categorySlug);
        } else {
            $category = GraphicCategory::first();
        }

        $graphic = Graphic::findBySlug($slug);
        $frontSetting = $graphic->front_settings;

        return view('frontend.graphic-designs.category', [
            'graphic' => $graphic,
            'category' => $category,
            'categories' => $categories,
            'frontSetting' => $frontSetting,
            'categorySlug' => $categorySlug,
        ]);
    }

    public function getGraphicDesigns($slug)
    {
        $graphic = Graphic::findBySlug($slug);
        $categories = GraphicCategory::where('graphic_id', $graphic->id)
            ->with(['designs' => function ($query) {
                $query->where('status', 1);
            }])->get();

        return $this->jsonSuccess([
            'categories' => $categories,
        ]);
    }

    public function chooseDesign($designHash)
    {
        $design = GraphicDesign::where('hash', $designHash)->first();
        $userDesign = $this->userDesign->createByDesign($design);

        if (request()->wantsJson()) {
            $data = $this->userDesign->getEditorData($userDesign->hash);

            return $this->jsonSuccess($data);
        }

        return redirect()->to(route('graphics.edit', $userDesign->hash));
    }

    public function editDesign($designHash)
    {
        return view('frontend.graphic-designs.editor', [
            'designHash' => $designHash,
        ]);
    }

    public function getUserDesign($designHash)
    {
        try {
            $data = $this->userDesign->getEditorData($designHash);

            return $this->jsonSuccess($data);
        } catch (\Exception $exception) {
            return $this->jsonExceptionError($exception);
        }
    }

    public function saveDesign(Request $request, $designHash)
    {
        try {
            $data = $request->validate([
                'svgData' => 'required',
            ]);

            if (auth()->check()) {
                $this->userDesign->synchronize($data['svgData'], $designHash);
            } else {
                $this->userDesign->syncGuestDesign($data['svgData'], $designHash);
            }

            $data = $this->userDesign->getEditorData($designHash);

            return $this->jsonSuccess($data);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function getPresetCategory()
    {
        try {
            $categories = ColorCategory::withCount('palettes')
                ->where("status", 1)
                ->orderBy("order")
                ->get(['id', 'name', 'slug', 'status', 'order', 'gradient', 'palettes_count']);

            if (auth()->check()) {
                $userGradient = UserPalette::where('user_id', auth()->user()->id)->where('gradient', 1)->get();
                $userSolid = UserPalette::where('user_id', auth()->user()->id)->where('gradient', 0)->get();
                $palettes['mine'] = Palette::where('user_id', auth()->user()->id)->get();
                $username = auth()->user()->username;
            }
            $palettes['admin'] = Palette::whereNull('user_id')->get();

            $gradient = $categories->where("gradient", 1);
            $solid = $categories->where("gradient", 0);

            return response()->json([
                'status' => 1,
                'gradient' => $gradient,
                'userGradient' => $userGradient ?? [],
                'userSolid' => $userSolid ?? [],
                'username' => $username ?? '',
                'solid' => $solid,
                'palettes' => $palettes ?? [],
            ]);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function saveFinal(Request $request, $designHash)
    {
        try {
            $request->validate([
                'version_type' => 'required',
                'version_name' => 'required',
            ]);

            $version_type = $request->version_type;
            $version_name = $request->version_name;
            $data = [];

            if (auth()->check()) {
                $userDesign = UserDesign::where("hash", $designHash)->first();

                if (empty($userDesign)) {
                    $userDesign = $this->userDesign->getSessionDesign($designHash);
                    $this->userDesign->saveUserDesignFromGuestDesign($request->svgData, $userDesign, $version_name ?? '');
                } else {
                    if ($version_type === 'create') {
                        $data['newHash'] = $this->userDesign->createNewVersion($request->svgData, $userDesign, $version_name ?? 'default');
                    } else {
                        $this->userDesign->synchronize($request->svgData, $designHash);
                    }
                }
            } else {
                $this->userDesign->syncGuestDesign($request->svgData, $designHash);
                session()->put(['url.intended' => route('graphics.edit', $designHash)]);
            }

            return $this->jsonSuccess($data);
        } catch (\Exception $exception) {
            return $this->jsonExceptionError($exception);
        }
    }

    public function downloadPreview(DesignService $service, string $designHash)
    {
        $userDesign = $this->userDesign->getSessionDesign($designHash);
        if (!$userDesign) {
            // Actualize logo preview
            $userDesign = UserDesign::where("hash", $designHash)->firstorfail();
            dispatch(new ActualizeDesignPreview($userDesign));
        }

        $user = user();
        $accessToPremium = false;
        if ($user && $user->packages->where('status', 'active')->first()) {
            $accessToPremium = true;
        }

        if ((!$accessToPremium) && $userDesign->design->premium) {
            session()->put(['design' => $userDesign]);

            return response()->json([
                'type' => HttpStatusEnum::HTTP_SUCCESS,
                'isPurchased' => false,
                'message' => 'This is premium design or you are out of free design download limits. Please purchase package.',
                'redirect' => route('package.index'),
            ]);
        }

        $preview = $service->getDesignPreview($userDesign);

        return response()->json([
            'preview' => $preview,
        ]);
    }

    public function downloadDesign(DesignService $service, string $designHash): object
    {
        if (!$userDesign = $this->userDesign->getSessionDesign($designHash)) {
            $userDesign = UserDesign::where("hash", $designHash)->firstorfail();
            dispatch(new ActualizeDesignPreview($userDesign));
        }

        $user = user();
        $accessToPremium = false;
        if ($user && $user->packages->where('status', 'active')->first()) {
            $accessToPremium = true;
        }

        if ((!$accessToPremium) && $userDesign->design->premium) {
            session()->put(['design' => $userDesign]);

            return response()->json([
                'type' => HttpStatusEnum::HTTP_SUCCESS,
                'isPurchased' => false,
                'message' => 'This is premium design or you are out of free design download limits. Please purchase package.',
                'redirect' => route('package.index'),
            ]);
        }

        $content = $service->getDesignPreview($userDesign);

        return response()->json([
            'type' => HttpStatusEnum::HTTP_SUCCESS,
            'isPurchased' => true,
            'content' => $content,
        ]);
    }

    public function downloadPackage(string $designHash): object
    {
        if (!$userDesign = $this->userDesign->getSessionDesign($designHash)) {
            $userDesign = UserDesign::where("hash", $designHash)->firstorfail();
        } else {
            $userDesign = $this->userDesign->saveUserDesignFromGuestDesign(null, $userDesign);
        }

        $user = user();
        $accessToPremium = false;
        if ($user && $user->packages->where('status', 'active')->first()) {
            $accessToPremium = true;
        }

        if ((!$accessToPremium) && $userDesign->design->premium) {
            return redirect()->route('package.index')
                ->with("info", "This is a premium design or you are out of free design download limits. Please purchase package.");
        }

        $userDesign = $userDesign->setAsInProgress();

        dispatch(new SendDesignPackageToClient($userDesign))->onQueue('high');

        return redirect()->route("user.graphics.index")
            ->with("success", "Success, A system will generate full package in a few minutes.");
    }

    public function getDesignPreviews(Request $request)
    {
        $loadedDesigns = (array)$request->get('loaded_designs');
        $total = UserDesign::where("user_id", user()->id)->count();

        return response()->json([
            'designs' => $this->userDesign->getLiveDesigns($loadedDesigns),
            'total' => $total,
        ]);
    }
}
