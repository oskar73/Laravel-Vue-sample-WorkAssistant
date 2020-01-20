<?php


namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait Crud
{
    abstract public function model();

    abstract public function index(Request $request);

    public function store(Request $request): JsonResponse
    {
        $modelName = $this->model();

        if ($request->id) {
            $data = $modelName::find($request->id);
        } elseif ($request->slug) {
            $data = $modelName::where('slug', $request->slug)->first();
        } else {
            $data = new $modelName();
        }

        $validator = Validator::make($request->all(), $data->rule($request));

        if ($validator->passes()) {
            $data->store($request);

            return $this->jsonSuccess([
                'data' => $data,
                'action' => $request->id ? 'update':'create',
                'tableData' => $data->dataTableRow(),
                'view' => $data->renderView(),
            ]);
        }

        return $this->jsonError(['errors' => $validator->errors()->all()]);
    }

    public function delete($id): JsonResponse
    {
        $modelItem = $this->model()::find($id);
        $modelItem->delete();

        return $this->jsonSuccess();
    }

    public function deleteAll(Request $request): JsonResponse
    {
        $ids = explode(',', $request->ids);
        $this->model()::destroy($ids);

        return $this->jsonSuccess();
    }

    public function jsonSuccess($data = []): JsonResponse
    {
        return response()->json(array_merge(['status' => 1], $data));
    }

    public function jsonError($data = []): JsonResponse
    {
        return response()->json(array_merge(['status' => 0], $data));
    }

    public function jsonExceptionError($e): JsonResponse
    {
        return response()->json([
            'status' => 0,
            'errors' => [json_encode($e->getMessage())],
        ]);
    }
}
