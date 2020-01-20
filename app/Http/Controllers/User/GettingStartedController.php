<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

class GettingStartedController extends UserController
{
    public function index()
    {
        if (user()->started == 1) {
            return redirect()->route('user.todo.index');
        }

        return view('user.started.index');
    }
    public function welcome()
    {
        if (user()->started == 1) {
            return redirect()->route('user.todo.index');
        }

        return view('user.started.welcome');
    }
    public function username(Request $request)
    {
        try {
            $validation = \Validator::make($request->all(), [
                'username' => 'required|min:4|unique:users,username,'.user()->id,
                'pin_number' => 'required|string|min:4|max:7|unique:users,pin_number,'.user()->id,
            ]);
            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            user()->username = $request->username;
            user()->pin_number = $request->pin_number;
            user()->save();

            return $this->jsonSuccess(user()->getCompletedPercentage());
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
    public function demographics(Request $request)
    {
        try {
            $validation = \Validator::make($request->all(), [
                'address1' => 'required|max:191',
                'address2' => 'nullable|max:191',
                'city' => 'required|max:191',
                'state' => 'required|max:191',
                'zipcode' => 'required|postal_code:' . $request->country,
                'country' => 'required|exists:mysql2.country,iso',
                'latitude' =>  'required|string',
                'longitude' => 'required|string',
            ], [
                'latitude.required' => 'Please select an address from the suggested options to automatically fill in the Address 1 details.',
            ]);

            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            $address = new \stdClass();
            $address->address1 = $request->address1;
            $address->address2 = $request->address2;
            $address->city = $request->city;
            $address->state = $request->state;
            $address->zipcode = $request->zipcode;
            $address->country = $request->country;
            $address->latitude = $request->latitude;
            $address->longitude = $request->longitude;
            user()->address = json_encode($address);
            user()->save();

            return $this->jsonSuccess(user()->getCompletedPercentage());
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
    public function time(Request $request)
    {
        try {
            $validation = \Validator::make($request->all(), [
                'timezone' => 'required|max:191',
                'timeformat' => 'required|max:191',
            ]);
            if ($validation->fails()) {
                return $this->jsonError($validation->errors());
            }

            user()->timezone = $request->timezone;
            user()->timeformat = $request->timeformat;
            user()->save();

            return $this->jsonSuccess(user()->getCompletedPercentage());
        } catch (\Exception $e) {
            return $this->jsonExceptionError($e);
        }
    }
    public function complete()
    {
        if (user()->getCompletedPercentage() == 100 && user()->started == 0) {
            user()->started = 1;
            user()->save();

            return redirect()->route("user.todo.index")->with("success", "Success!");
        }

        return back();
    }
}
