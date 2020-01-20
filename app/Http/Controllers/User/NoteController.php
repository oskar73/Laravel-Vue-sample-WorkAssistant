<?php

namespace App\Http\Controllers\User;

use App\Models\Note;
use Illuminate\Http\Request;
use Validator;

class NoteController extends UserController
{
    public function __construct(Note $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $notes = $this->model->my()->latest()->get();
        $data = view("components.account.note", compact("notes"))->render();

        return response()->json([
            'status' => 1,
            'data' => $data,
        ]);
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                "note" => 'required|max:191',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                ]);
            }
            $count = $this->model->my()->count();
            if ($count > 20) {
                return response()->json([
                    'status' => 0,
                    'data' => ['You can save max 20 notes. Please delete old ones'],
                ]);
            }
            $this->model->create([
                'user_id' => user()->id,
                'text' => $request->note,
            ]);

            return response()->json([
                'status' => 1,
                'data' => 1,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function toggle(Request $request)
    {
        $note = $this->model->my()->whereId($request->id)->firstorfail();

        if ($request->action == 'remove') {
            $note->delete();
        } else {
            if ($note->checked == 1) {
                $note->checked = 0;
            } else {
                $note->checked = 1;
            }
            $note->save();
        }

        return response()->json([
            'status' => 1,
            'data' => 1,
        ]);
    }
}
