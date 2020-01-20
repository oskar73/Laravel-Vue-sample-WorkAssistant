<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Website\Page;
use App\Models\Website\PageSection;
use App\Models\Website\UserTemplates;
use Illuminate\Http\Request;

class UserTemplateController extends Controller
{
    public function __construct(UserTemplates $model)
    {
        $this->model = $model;
    }

    public function getUserTemplates($webId): \Illuminate\Http\JsonResponse
    {
        try {
            $userTemplates = $this->model->where('web_id', $webId)->with(['pages' => function ($query) {
                return $query->where('status', 0)->orderBy('order');
            }])->get();

            return $this->jsonSuccess($userTemplates);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function createUserTemplate(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->model->createUserTemplate($request->all());

            return $this->jsonSuccess([
                'success' => true,
            ]);
        } catch (\Throwable $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function getUserTemplate($webId, $id): \Illuminate\Http\JsonResponse
    {
        try {
            if ($id) {
                $result = $this->model->where('id', $id)->with(['pages' => function ($query) use ($webId) {
                    return $query->where('status', 0)->orderBy('order');
                }])->first();
            } else {
                $result = (new \App\Models\Website)->where('id', $webId)->with(['pages' => function ($query) {
                    return $query->where('status', 0)->orderBy('order');
                }])->first();
            }

            return $this->jsonSuccess($result);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function deleteUserTemplate($webId, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->model->find($id)->delete();
            (new \App\Models\Website\Page)->where('user_template_id', $id)->delete();

            // PageSection::find($page_id)

            return $this->jsonSuccess([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
}
