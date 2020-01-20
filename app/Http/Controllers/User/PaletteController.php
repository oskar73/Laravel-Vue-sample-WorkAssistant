<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\PaletteCategoryRepository;
use App\Repositories\PaletteRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaletteController extends Controller
{
    private PaletteRepository $repository;
    private PaletteCategoryRepository $categoryRepository;

    public function __construct(PaletteRepository $repository, PaletteCategoryRepository  $categoryRepository)
    {
        $this->repository = $repository;
        $this->categoryRepository = $categoryRepository;
    }

    public function categoriesView($type)
    {
        if ($type === 'simple') {
            return view('user.palettes.simple.category');
        }

        return view('user.palettes.advanced.category');
    }

    public function categoriesDataTable($type)
    {
        $categories = $this->categoryRepository->userCategories($type);

        $activeCats = $categories->where('status', 1);
        $inactiveCats = $categories->where('status', 0);

        $all = view('components.admin.palettesCategory', [
            'categories' => $categories,
            'selector' => "datatable-all",
        ])->render();

        $active = view('components.admin.palettesCategory', [
            'categories' => $activeCats,
            'selector' => "datatable-active",
        ])->render();

        $inactive = view('components.admin.palettesCategory', [
            'categories' => $inactiveCats,
            'selector' => "datatable-inactive",
        ])->render();

        $count['all'] = $categories->count();
        $count['active'] = $activeCats->count();
        $count['inactive'] = $inactiveCats->count();

        return response()->json([
            'status' => 1,
            'all' => $all,
            'active' => $active,
            'inactive' => $inactive,
            'count' => $count,
        ]);
    }

    public function sortView($type)
    {
        $categories = $this->categoryRepository->userCategories($type);
        $view = "";
        foreach ($categories as $item) {
            $view .= '<li data-id="' . $item->id . '">' . $item->name . '</li>';
        }

        return response()->json(compact('view'));
    }

    public function palettesView($type)
    {
        if ($type === 'simple') {
            return view('user.palettes.simple.palettes');
        }

        return view('user.palettes.advanced.palettes');
    }

    public function palettesData($type)
    {
        $categories = $this->categoryRepository->userCategories($type);
        $palettes = $this->repository->userPalettes($type);

        return $this->jsonSuccess([
            'palettes' => $palettes,
            'categories' => $categories,
        ]);
    }

    public function categorySwitch(Request $request)
    {
        $action = $request->action;
        $ids = $request->ids;
        $this->categoryRepository->switch($action, $ids);

        return $this->jsonSuccess();
    }

    public function categoryStore(Request $request, $type)
    {
        $rule = [
            'name' => 'required',
            'description' => 'max:1200',
        ];

        if ($request->id) {
            $rule['id'] = 'integer';
        } else {
            $rule['image'] = 'required';
        }

        $validation = Validator::make($request->all(), $rule);

        if ($validation->fails()) {
            return $this->jsonError($validation->errors());
        }

        $data = $request->only('name', 'description', 'status', 'image', 'id', 'type');
        $data['type'] = $type;
        $data['user_id'] = user()->id;
        $data['status'] = isset($data['status']);

        $category = $this->categoryRepository->save($data);

        return $this->jsonSuccess($category);
    }


    public function sortCategories(Request $request)
    {
        $sorts = $request->get('sorts');
        $this->categoryRepository->sort($sorts);

        return $this->jsonSuccess();
    }

    public function paletteStore(Request $request, $type)
    {
        $data = $request->only(['name', 'data', 'category_id', 'mode', 'image', 'status']);
        // form data submission has string type of data
        $data['name'] = $this->repository->model->getUniqueValue('name', $data['name']);
        if (is_string($data['data'])) {
            $data['data'] = json_decode($data['data']);
        }
        $data['type'] = $type;
        $data['user_id'] = user()->id;
        $newPalette = $this->repository->create($data);

        return $this->jsonSuccess([
            'palette' => [
                'id' => $newPalette->id,
                'name' => $newPalette->name,
                'category_id' => $newPalette->category_id,
                'mode' => $newPalette->mode,
                'type' => $type,
                'colors' => $newPalette->data,
            ],
        ]);
    }

    public function paletteUpdate(Request $request)
    {
        $data = $request->only(['name', 'data', 'category_id', 'mode', 'image', 'status']);
        // form data submission has string type of data
        $data['data'] = json_decode($data['data']);
        $palette = $this->repository->find($request->id);
        $palette->update($data);

        return $this->jsonSuccess([
            'palette' => $palette,
        ]);
    }

    public function palettesSort(Request $request)
    {
        $sorts = $request->sorts;
        $this->repository->sort($sorts);

        return $this->jsonSuccess();
    }

    public function paletteDelete(Request $request)
    {
        $this->repository->delete($request->id);

        return $this->jsonSuccess();
    }
}
