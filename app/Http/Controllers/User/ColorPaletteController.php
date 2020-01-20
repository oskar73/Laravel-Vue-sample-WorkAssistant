<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserPalette;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ColorPaletteController extends Controller
{
    public function index()
    {
        if (request()->wantsJson()) {
            $gradientItems = UserPalette::where("user_id", user()->id)->where('gradient', 1)->orderBy("order")->latest()->get();
            $solidItems = UserPalette::where("user_id", user()->id)->where('gradient', 0)->orderBy("order")->latest()->get();

            $gradient = view("components.user.colorPalette", [
                "palettes" => $gradientItems,
            ])->render();

            $solid = view("components.user.colorPalette", [
                "palettes" => $solidItems,
            ])->render();

            $count['gradient'] = $gradientItems->count();
            $count['solid'] = $solidItems->count();

            return response()->json([
                'status' => 1,
                'gradient' => $gradient,
                'solid' => $solid,
                'count' => $count,
            ]);
        }

        return view('user.color-palettes.index');
    }

    public function create($type)
    {
        if ($type === 'solid') {
            return view('user.color-palettes.createSolid');
        }

        return view('user.color-palettes.createGradient');
    }

    public function store(Request $request, $type)
    {
        try {
            if ($type === 'gradient') {
                $validation = Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:191',
                        'attrs' => 'required',
                        'svg' => 'required',
                    ]
                );
                if ($validation->fails()) {
                    return $this->jsonError($validation->errors());
                }

                if ($request->palette_id) {
                    $palette = UserPalette::find($request->palette_id);
                } else {
                    $palette = new UserPalette();
                }
                $palette->user_id = auth()->user()->id;
                $palette->gradient = 1;
                $palette->name = $request->name;
                $palette->preview = $request->svg;
                $palette->data = $request->attrs;
                $palette->save();

                return $this->jsonSuccess();
            }


            $validation = Validator::make(
                $request->all(),
                [
                    'color1' => 'required|max:191',
                    'color2' => 'required|max:191',
                    'color3' => 'required|max:191',
                    'color4' => 'required|max:191',
                    'color5' => 'required|max:191',
                    'name' => 'required|max:191',
                ]
            );
            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            if ($request->palette_id) {
                $palette = UserPalette::find($request->palette_id);
            } else {
                $palette = new UserPalette();
            }

            $palette->user_id = auth()->user()->id;
            $palette->gradient = 0;
            $palette->name = $request->name;
            $palette->data = json_encode([
                'color1' => $request->color1,
                'color2' => $request->color2,
                'color3' => $request->color3,
                'color4' => $request->color4,
                'color5' => $request->color5,
            ]);
            $palette->save();

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function edit(UserPalette $userPalette)
    {
        if ($userPalette->gradient) {
            return view('user.color-palettes.editGradient', compact('userPalette'));
        }

        return view('user.color-palettes.editSolid', compact('userPalette'));
    }

    public function delete(UserPalette $userPalette)
    {
        $userPalette->delete();

        return $this->jsonSuccess();
    }

    public function sortGet($type)
    {
        try {
            $gradient = $type === 'gradient' ? 1 : 0;

            $items = UserPalette::select('id', 'name')
                ->where('user_id', auth()->user()->id)
                ->where('gradient', $gradient)
                ->orderBy('order')
                ->get();

            $view = '';
            foreach ($items as $item) {
                $view .= '<li data-id="' . $item->id . '">' . $item->name . '</li>';
            }

            return $this->jsonSuccess($view);
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }

    public function sortStore(Request $request)
    {
        try {
            $sorts = $request->get('sorts');

            foreach ($sorts as $key => $sort) {
                $item = UserPalette::where("user_id", auth()->user()->id)->whereId($sort)->first();
                $item->order = $key + 1;
                $item->save();
            }

            return $this->jsonSuccess();
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
}
