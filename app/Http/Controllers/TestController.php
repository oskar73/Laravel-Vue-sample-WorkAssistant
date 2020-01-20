<?php

namespace App\Http\Controllers;

use App\Models\Builder\SectionCategory;
use App\Models\GraphicDesign\UserDesign;
use App\Repositories\GraphicDesignRepository;
use App\Repositories\UserDesignRepository;
use App\Repositories\UserRepository;
use App\Services\BrowserShot;
use App\Services\DesignService;
use App\Services\NamecheapService;
use Illuminate\Http\Request;
use Session;

class TestController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $command = $request->get('command');
        if ($command) {
            $res = shell_exec($command);
            dd($res);
        }
        dd('here');
    }

    public function screenshot(Request $request, DesignService $service)
    {
        try {
            $chromePath = $request->get('chrome');
            $nodePath = $request->get('node');

            $userDesign = UserDesign::latest()->first();
            $svg = $service->getSvgForPreview($userDesign);

            $bs = BrowserShot::html($svg->toXMLString());
            if ($nodePath) {
                $bs = $bs->setNodeModulePath($nodePath);
            }

            if ($chromePath) {
                $bs = $bs->setChromePath($chromePath);
            }

            $encoded = $bs
                ->hideBackground()
                ->windowSize((int)$svg->getDocument()->getAttribute('width'), (int)$svg->getDocument()->getAttribute('height'))
                ->setScale(1)
                ->noSandbox()
                ->screenshot();

            return response()->json([
                'encoded' => base64_encode($encoded),
                'string' => $svg->toXMLString(),
            ]);
        } catch (\Exception $e) {
            dd([
                'error' => $e,
            ]);
        }
    }

    public function domainCheck($domain = null)
    {
        if (!$domain) {
            dd('No domain provided');
        }

        try {
            $service = new NamecheapService();
            $response = $service->getHost($domain);

            dd($response);
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    public function getSectionCategories()
    {
        $categories = SectionCategory::with('sections')->get();

        $categorySections = [];

        foreach ($categories as $category) {
            $sections = [];
            foreach ($category->sections as $section) {
                $sections[] = [
                    'name' => $section->name,
                    'data' => $section->data,
                ];
            }
            $categorySections[] = [
                'name' => $category->name,
                'description' => $category->name . ' Category',
                'module' => $category->module,
                'sections' => $sections,
            ];
        }

        return $this->jsonSuccess($categorySections);
    }

    public function userData()
    {
        $todo = 'appointment';
        $todos = $this->userRepository->getTodos($todo);

        return $todos;
    }
}
