<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UpdateQuickTourRequest;
use App\Models\QuickTour;
use Illuminate\Http\Request;
use Validator;

class QuickTourController extends AdminController
{
    public function __construct(QuickTour $model)
    {
        $this->model = $model;
        $this->sortModel = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (request()->wantsJson()) {
            $items = $this->model->orderBy('order')->get();
            $selector = "datatable-all";
            $view = view('components.admin.quickTourTable', compact('items', 'selector'))->render();
            $count['all'] = $items->count();

            return response()->json([
                'status' => 1,
                'all' => $view,
                'count' => $count,
            ]);
        }

        $data['targetIDs'] = $this->model->getTargetIDs();

        return view(self::$viewDir.'quick-tour.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreQuickTourRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            $validation = Validator::make(
                $request->all(),
                $this->model->storeRule($request),
                $this->model::CUSTOM_VALIDATION_MESSAGE
            );
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
            $item = $this->model->storeItem($request);

            return response()->json([
                'status' => 1,
                'data' => $item,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuickTour  $quickTour
     * @return \Illuminate\Http\Response
     */
    public function show(QuickTour $quickTour)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QuickTour  $quickTour
     * @return \Illuminate\Http\Response
     */
    public function edit(QuickTour $quickTour)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateQuickTourRequest  $request
     * @param  \App\Models\QuickTour  $quickTour
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuickTourRequest $request, QuickTour $quickTour)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuickTour  $quickTour
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuickTour $quickTour)
    {
        //
    }

    public function getSort()
    {
        try {
            $items = $this->sortModel->select('id', 'title')->orderBy('order')->get();
            $view = '';
            foreach ($items as $item) {
                $view .= '<li data-id="'.$item->id.'">'.$item->title.'</li>';
            }

            return response()->json(compact('view'));
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    /**
     * Return available target ids for quick tour
     *
     * @return Json
     */
    public function getTargetIds()
    {
        try {
            $targetIDs = $this->model->getTargetIDs();
            $assignedTargetIDs = $this->model->all()->pluck('targetID')->toArray();
            $unassignedTargetIDs = array_diff_key($targetIDs, array_flip($assignedTargetIDs));

            $datas = [];
            foreach ($unassignedTargetIDs as $key => $value) {
                $obj = (object)[];
                $obj->id = $key;
                $obj->text = $value;
                array_push($datas, $obj);
            }

            return response()->json(["results" => $datas, "pagination" => ["more" => false]]);
        } catch (\Exception $e) {
            // return $this->jsonExceptionError($e);
            dump($e->getMessage());

            return response()->json(["results" => collect([]), "pagination" => ["more" => false]]);
        }
    }
}
