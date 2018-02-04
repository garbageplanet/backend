<?php

namespace App\Http\Controllers\Auth;

use Log;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

// class AuthenticateController extends AuthController
class AuthenticateController extends AuthController
{
    public function __construct()
    {
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
        $this->middleware('jwt.auth', ['except' => ['getLogout', 'authenticate', 'postRegister', 'loginUser', 'deleteUser']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::all();
        return $users;
    }

    /**
     * Login user
     * @param Request $request
     * @return $token
     */
    public function authenticate(Request $request)
    {
        Log::debug('authenticate start');

        $credentials = $request->only('email', 'password');
        $res = $this->loginUser($credentials);

        if ($res == 401) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        if ($res == 500) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // Log::debug('authenticate return: ' . $res);
        return response()->json(['token' => $res], 200);
    }

    /**
     * Authenticates user
     * @param Request $request
     * @return $user
     */
    public function getAuthenticatedUser($token = null)
    {
        Log::debug('getAuthenticatedUser start');

        if ($token != null) {
            JWTAuth::setToken($token);
        }

        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }

    /**
     * Register user
     * @param Request $request
     * @return token
     */
    public function postRegister(Request $request)
    {
        $validator = AuthController::validator($request->all());

        if ($validator->fails()) {
            return response()->json(['Something went wrong.'], 403);
        }
        //create user
        $user = AuthController::create($request->all());

        //credentials for login
        $credentials = $request->only('email', 'password');

        $token = $this->loginUser($credentials);

        return response()->json(compact('token'));
    }

    /**
     * Remove user
     * @param Request $request
     * @return $token
     */
    public function deauthenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $res = $this->deleteUser($credentials);

        return response()->json("{}", $res);
    }

    /**
     * Login user
     * @param Array $attributes
     */
    protected function loginUser($credentials)
    {
        try {
            $token = JWTAuth::attempt($credentials);

            if (!$token) {
                return 401;
            }

        } catch (JWTException $e) {
            return 500;
        }

        return $token;
    }

    protected function deleteUser($credentials)
    {
        try {
            $token = JWTAuth::attempt($credentials);

            if (! $token)
            {
                return 401;
            }

        } catch (JWTException $e) {
            return 500;
        }

        $userId = JWTAuth::toUser()->id;

        $user = User::findOrFail($userId);

        $user->delete();

        return 200;

    }

    public function getLogout($token = null)
    {

        if ($token != null) {

            JWTAuth::setToken($token)->invalidate();

            return response()->json("{}", 200);
        }

    }
}
