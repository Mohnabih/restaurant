<?php

namespace App\Http\Controllers\Api\User;

use App\ApiCode;
use App\Http\Controllers\Api\AppBaseController;
use App\Http\Requests\Api\Auth\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends AppBaseController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\Auth\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        try {
            User::create($request->validated());
            return $this->sendResponse(
                null,
                'User register successfully',
                ApiCode::SUCCESS,
                0
            );
        } catch (\Illuminate\Database\QueryException $ex) {
            $message = $ex->getMessage();
            return $this->sendResponse(
                null,
                $message,
                ApiCode::BAD_REQUEST,
                1
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $authUser = Auth::user();
        if ($authUser->id == $id || $authUser->role == 1) {
            try {
                $user = User::findOrFail($id);
                $user->delete();
                return $this->sendResponse(
                    null,
                    'User deleted successfully',
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
        } else {
            return $this->sendResponse(
                null,
                "Don't have permission!",
                ApiCode::UNAUTHORIZED,
                1
            );
        }
    }
}
