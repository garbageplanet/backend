<?php

/*
 * Authentication for soft accounts
 * FIXME this needs to be made into an Auth controller
 *
 */

namespace App\Http\Controllers\Glome;

use Illuminate\Http\Request;

use Log;
use Glome;
use JWTAuth;
use App\Models\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthenticateController;

class GlomeController extends AuthenticateController //Controller
{
    public function __construct()
    {
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
        $this->middleware('jwt.auth', ['except' => ['createSoftAccount', 'showSoftAccount']]);
    }

    public function createSoftAccount()
    {
        $ret = Glome::createGlomeAccount();
        $account = json_decode($ret);

        if ($account and $account->glomeid) {
            // received soft account -> create a proper local account
            Log::Debug('GlomeController::create ret from Glome:' . $ret);

            $user = User::create([
                'name' => $account->glomeid,
                'email' => $account->token . '@nowhere',
                'password' => bcrypt($account->glomeid)
            ]);

            if ($user) {
                // login this user
                Log::Debug('GlomeController::create local user:' . $user);
                $credentials = ['email' => $user->email, 'password' => $account->glomeid];
                Log::Debug('GlomeController::create credentials:' . implode("|", $credentials));
                $token = $this->loginUser($credentials);
                Log::Debug('GlomeController::create try token: ' . $token);

                if ($token) {
                    $authenticatedUser = JWTAuth::authenticate($token);

                    if ($authenticatedUser) {
                        Log::Debug('GlomeController::authenticatedUser: ' . $authenticatedUser);
                        $ret = json_encode(['user' => $authenticatedUser, 'token' => $token]);
                    }
                }
            }
        }

        return $ret;
    }

    public function showSoftAccount($glomeid)
    {
        $glome = Glome::showGlomeAccount($glomeid);
        return $glome;
    }
}
