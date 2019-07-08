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
    /**
     * Default token TTL (Minutes)
     * @var int
     */
    CONST AUTH_TTL = 1;

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
        $this->middleware('guest')->except([
            'logout',
            'refresh',
            'me'
        ]);
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
            $token = auth()->setTTL(self::AUTH_TTL)->attempt($credentials);

            if ($token) {
                $result = [
                    'access_token' => $token,
                    'expires_in_seconds' => (self::AUTH_TTL * 60)
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

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logout(Request $request, Responder $responder)
    {
        auth()->logout();

        return $responder->success([
            'message' => 'Usuário deslogado com sucesso.'
        ])->respond();
    }

    /**
     * @param Request $request
     * @param Responder $responder
     * @return JsonResponse
     */
    public function refresh(Request $request, Responder $responder)
    {
        $newToken = auth()->setTTL(self::AUTH_TTL)->refresh();

        return $responder->success([
            'access_token' => $newToken,
            'expires_in_seconds' => (self::AUTH_TTL * 60)
        ])->respond();
    }

    public function me(Request $request, Responder $responder)
    {

        $user = JWTAuth::user();
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ];
        return $responder->success($data);
    }
}
