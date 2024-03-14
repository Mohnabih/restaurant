<?php

namespace App\Http\Controllers\Api\Auth;

use App\ApiCode;
use App\Http\Controllers\Api\AppBaseController;
use App\Http\Requests\Api\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends AppBaseController
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \App\Http\Requests\Api\Auth\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken($request->device_name)->plainTextToken;
            return $this->sendResponse(
                [
                    "token" => $token,
                    "user" => $user
                ],
                'User login successfully',
                ApiCode::SUCCESS,
                0
            );
        } else {
            return $this->sendResponse(
                null,
                'Unauthorized',
                ApiCode::UNAUTHORIZED,
                1
            );
        }
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        try {
            Session::flush();
            Auth::logout();
            return $this->sendResponse(
                null,
                'Successfully logged out',
                ApiCode::SUCCESS,
                0
            );
        } catch (\Illuminate\Database\QueryException $ex) {
            $message = $ex->getMessage();
            return $this->sendResponse(
                null,
                $message,
                ApiCode::SOMETHING_WENT_WRONG,
                1
            );
        }
    }
}
