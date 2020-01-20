<?php

namespace App\Http\Controllers\Admin\Theme;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Theme;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ThemeItemController extends AdminController
{
    public function __construct(Theme $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $themes = $this->model->whereNull('user_id')->get();

            $activeThemes = $themes->where('status', 1);
            $inactiveThemes = $themes->where('status', 0);

            $all = view('components.admin.themeItem', [
                'themes' => $themes,
                'selector' => 'datatable-all',
            ])->render();

            $active = view('components.admin.themeItem', [
                'themes' => $activeThemes,
                'selector' => 'datatable-active',
            ])->render();

            $inactive = view('components.admin.themeItem', [
                'themes' => $inactiveThemes,
                'selector' => 'datatable-inactive',
            ])->render();

            $count['all'] = $themes->count();
            $count['active'] = $activeThemes->count();
            $count['inactive'] = $inactiveThemes->count();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'active' => $active,
                'inactive' => $inactive,
                'count' => $count,
            ]);
        }

        return view('admin.theme.item');
    }

    /**
     * Creates template
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), $this->model->storeRule($request), $this->model::CUSTOM_VALIDATION_MESSAGE);
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }

            $data = $request->all();
            $data['name'] = $this->model->getUniqueValue('name', $data['name'], ['user_id' => null]);
            $data['user_id'] = null; // for admin, user_id is null
            $theme = $this->model->create($data);

            return $this->jsonSuccess(['theme' => $theme]);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function update(Request $request)
    {
        try {
            $data = $request->all();
            $theme = Theme::find($data['id']);
            $data['user_id'] = null; // for admin, user_id is null
            $theme->update($data);

            return $this->jsonSuccess(['theme' => $theme]);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function switch(Request $request)
    {
        try {
            $this->validate($request, [
                'action' => 'required|string',
                'ids' => 'required|array',
            ]);
            $data = $request->all();

            $query = $this->model->whereIn('id', $data['ids']);

            switch ($data['action']) {
                case 'active':
                    $query->update(['status' => 1]);

                    break;
                case 'inactive':
                    $query->update(['status' => 0]);

                    break;
                case 'featured':
                    $query->update(['featured' => 1]);

                    break;
                case 'unfeatured':
                    $query->update(['featured' => 0]);

                    break;
                case 'new':
                    $query->update(['new' => 1]);

                    break;
                case 'undonew':
                    $query->update(['new' => 0]);

                    break;
                case 'delete':
                    $query->delete();

                    break;
            }

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function delete(Request $request)
    {
        Theme::destroy([$request->themeId]);

        return $this->jsonSuccess();
    }
}
