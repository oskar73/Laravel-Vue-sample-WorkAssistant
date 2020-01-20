<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Sso;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');

        $nocaptcha = optional(option("nocaptcha"));

        config()->set("captcha.secret", $nocaptcha['secret']);
        config()->set("captcha.sitekey", $nocaptcha['sitekey']);

        if (config('app.url')) {
            $this->redirectTo = config('app.url')."/home";
        }
    }

    public function showLoginForm(Request $request)
    {
        if ($request->redirect_uri) {
            $request->session()->put('redirect_uri', $request->redirect_uri);
        } else {
            if (Sso::isLoggedIn()) {
                return redirect($this->redirectTo);
            }
        }

        return view('frontend.auth.login');
    }

    protected function validateLogin(Request $request)
    {

        $rule = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];
        if (config('captcha.sitekey')) {
            $rule['g-recaptcha-response'] = 'required|captcha';
        }
        $request->validate($rule, [
            'g-recaptcha-response.*' => 'Please check this field.',
        ]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|void
     */
    protected function authenticated(Request $request, User $user)
    {
        if ($user->status !== 'active') {
            \Auth::logout();

            return redirect()->route('login')->with("message", "Your account is not active. Please contact admin");
        }
        if (! $user->isEmailVerified()) {
            $user->makeEmailVerified();
        }

        $user->saveLoginActivity();

        // set sso cookie
        Sso::setCookie();

        $redirect_uri = $request->session()->get('redirect_uri');

        if (! $redirect_uri) {
            $redirect_uri = $this->redirectTo;
        }

        $request->session()->forget('redirect_uri');
        //        $query = http_build_query([
        //            'user_id' => \auth()->user()->email,
        //            'psw' => \auth()->user()->getAuthPassword(),
        //            'username' => \auth()->user()->username,
        //        ]);

        return redirect($redirect_uri);
    }

    protected function loggedOut(Request $request)
    {
        Sso::logOut();

        return redirect()->to(config('app.url'));
    }
}
