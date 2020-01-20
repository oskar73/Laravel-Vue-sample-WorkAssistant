<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\SendDesignPackageToClient;
use App\Models\GraphicDesign\Graphic;
use App\Models\GraphicDesign\UserDesign;
use App\Services\GenerateDesignPackageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class GraphicDesignsController extends Controller
{
    public UserDesign $model;

    public function __construct(UserDesign $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            return $this->model->getDatatable(request("graphic_id"));
        }

        $graphicIds = UserDesign::whereNull('parent_id')
            ->groupBy('graphic_id')
            ->select('graphic_id')
            ->get()
            ->toArray();
        $userGraphics = Graphic::whereIn('id', $graphicIds)->get();

        return view('user.graphic-designs.index', [
            'userGraphics' => $userGraphics,
        ]);
    }

    public function editDesign($designHash): object
    {
        return view('frontend.graphic-designs.editor', [
            'designHash' => $designHash,
        ]);
    }


    public function live()
    {
        $graphics = Graphic::all();

        return view('frontend.graphic-designs.editor', [
            'graphics' => $graphics,
            'liveView' => true,
        ]);
    }

    public function downloadDesign($designHash): object
    {
        try {
            $design = $this->model->where("user_id", auth()->user()->id)
                ->where("hash", $designHash)
                ->firstorfail();

            $file = $design->preview()->first()->content;

            $user = user();
            $accessToPremium = false;
            $allowDownload = true;
            if($user && $user->packages->where('status', 'active')->first()){
                $accessToPremium = true;
            }

            if ((!$accessToPremium) && $design->design->premium) {
                $allowDownload = false;
            }

            if (request()->wantsJson()) {
                if(!$allowDownload){
                    return response()->json([
                        'type' => 'success',
                        'isPurchased' => false,
                        'message' => 'This is premium design or you are out of free design download limits. Please purchase package.',
                        'redirect' => route('package.index'),
                    ]);
                }
                return $this->jsonSuccess($file);
            } else {
                if(!$allowDownload){
                    return redirect()->route('package.index')
                        ->with("info", "This is a premium design or you are out of free design download limits. Please purchase package.");
                }

                $image = str_replace('data:image/png;base64,', '', $file);
                $image = str_replace(' ', '+', $image);
                $image = base64_decode($image);

                $headers = [
                    'Content-type' => 'image/png',
                    'Content-Disposition' => "attachment; filename=" . $designHash . ".png",
                ];

                return Response::make($image, 200, $headers);
            }
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function preview($designHash): object
    {
        $userFavicon = $this->model->where("user_id", auth()->user()->id)
            ->where("hash", $designHash)
            ->firstorfail()
            ->generatePreview();

        return Storage::disk($this->model::STORAGE_DISK)->response($userFavicon->getPreviewPath());
    }

    public function downloadPackage(GenerateDesignPackageService $service, $designHash)
    {
        try {
            $userDesign = $this->model->where("user_id", auth()->user()->id)
                ->where("hash", $designHash)
                ->firstorfail();

            $user = user();
            $accessToPremium = false;
            if($user && $user->packages->where('status', 'active')->first()){
                $accessToPremium = true;
            }

            if ((!$accessToPremium) && $userDesign->design->premium) {
                return redirect()->route('package.index')
                    ->with("info", "This is a premium design or you are out of free design download limits. Please purchase package.");
            }

            if ($userDesign->downloadable == 1) {
                $pathToArchive = $service->setUserDesign($userDesign)->getArchivePath();

                if(Storage::disk('s3-pub-bizinabox')->exists($pathToArchive)) {
                    return Storage::disk('s3-pub-bizinabox')->download($pathToArchive);
                }
            }

            $userDesign->setAsInProgress();

            dispatch(new SendDesignPackageToClient($userDesign))->onQueue('high');

            return back()->with("success", "Success! It will be downloadable in a few minutes.");
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function progress(Request $request)
    {
        if ($request->ajax()) {
            $ids = $request->ids;
            $result = [];
            foreach ($ids as $id) {
                $userDesign = UserDesign::where("user_id", auth()->user()->id)
                    ->where("id", $id)
                    ->first();

                if ($userDesign) {
                    $result[$id] = view("components.account.progress_design", compact("userDesign"))->render();
                }
            }

            return $this->jsonSuccess($result);
        } else {
            echo "invalid";
        }
    }

    public function delete($designHash)
    {
        try {
            $userDesign = $this->model->where("user_id", auth()->user()->id)
                ->where("hash", $designHash)
                ->firstorfail();

            $userDesign->delete();

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
}
