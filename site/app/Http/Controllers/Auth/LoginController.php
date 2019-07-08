<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Flugg\Responder\Responder;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Login route
     *
     * @param Request $request
     * @param Responder $responder
     * @return JsonResponse
     */
    public function login(Request $request, Responder $responder)
    {
        $credentials = $request->only('email', 'password');

        $result = [
            'message' => 'Acesso não autorizado'
        ];

        $success = false;

        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->validated()) {
            $token = JWTAuth::attempt($credentials);

            if ($token) {
                $result = [
                    'access_token' => $token,
                    'expires_in_seconds' => 60
                ];

                $success = true;
            }
        }

        if ($success) {
            return $responder
                ->success($result)
                ->respond();
        }

        return $responder
            ->error(403, 'Acesso não autorizado')
            ->respond(403);
    }
}
