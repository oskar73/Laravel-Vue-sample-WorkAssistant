<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(Request $request)
    {
        if ($request->redirect_uri) {
            $request->session()->put('redirect_uri', $request->redirect_uri);
        }

        return view('frontend.auth.register');
    }

    protected function registered(Request $request, $user)
    {
        if ($redirect_uri = $request->session()->get('redirect_uri')) {
            return redirect($redirect_uri);
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rule = [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'timezone' => ['string', 'max:40'],
        ];
        if (config('captcha.sitekey')) {
            $rule['g-recaptcha-response'] = 'required|captcha';
        }

        return Validator::make($data, $rule);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $username = explode('@', $data['email'])[0] ?? guid();
        $username = str_replace(".", "-", $username);

        $check = User::where("username", $username)->count();

        $user = new User();
        if (str_contains($data['name'], ' ')) {
            list($first_name, $last_name) = explode(' ', $data['name']);
            $user->first_name = $first_name;
            $user->last_name = $last_name;
        } else {
            $user->first_name = $data['name'];
        }
        $user->username = $check? guid():$username;
        $user->email = $data['email'];
        $user->timezone = $data['timezone'];
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }
}
