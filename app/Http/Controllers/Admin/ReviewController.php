<?php

namespace App\Http\Controllers\Admin;

use App\Models\NotificationTemplate;
use App\Models\Review;
use Illuminate\Http\Request;
use Validator;

class ReviewController extends AdminController
{
    public function __construct(Review $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            return $this->model->getDatatable(request()->get("status"));
        }

        return view(self::$viewDir.'review.index');
    }
    public function show($id)
    {
        $review = $this->model->findorfail($id);

        return view(self::$viewDir.'review.show', compact("review"));
    }
    public function edit(Request $request)
    {
        try {
            return response()->json([
                'status' => 1,
                'data' => $this->model->findorfail($request->id),
            ]);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
    public function updateRule($request)
    {
        $rule['rating'] = 'required|in:1,2,3,4,5';
        $rule['comment'] = 'required|min:5|max:600';

        return $rule;
    }
    public function update(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), $this->updateRule($request));
            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            $item = $this->model->findorfail($request->item_id);
            $item->rating = $request->rating;
            $item->comment = $request->comment;
            $item->status = $request->status?1:0;
            $item->save();
            if($item->status = 1){
              $this->sendNotification($item);
            }

            return $this->jsonSuccess($item);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
    public function sendNotification($review)
    {
        $notification = new NotificationTemplate();
        $data['url'] = url()->previous() . "#review";

        $name = $review->user->name ?? $review->name;
        $email = $review->user->email ?? $review->email;

        $data['username'] = $name;
        $data['detail'] = "Name: " .$name. " <br> Email: " . $email . "<br> Rating: {$review->rating} <br> Comment: {$review->comment}";
        $slug = $notification::REVIEW_APPROVED;
        $data['slug'] = $slug;
        $notification->sendNotification($data, $slug, $review->user);
    }

    public function switch(Request $request){
        $jsonResponse = parent::switch($request);
        foreach ($request->ids as $id) {
            $item = $this->model->findorfail($id);
            if($item->status = 1){
              $this->sendNotification($item);
            }
        }
        return $jsonResponse;
    }
}
