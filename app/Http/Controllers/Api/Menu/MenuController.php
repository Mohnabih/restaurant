<?php

namespace App\Http\Controllers\Api\Menu;

use App\ApiCode;
use App\Http\Controllers\Api\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends AppBaseController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\Menu\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->only('name');
        $user = Auth::user();

        if (!$user->menu) {
            $category = $user->menu()->create($input);
            return $this->sendResponse(
                $category,
                'Menu created successfully.',
                ApiCode::CREATED,
                0
            );
        } else
            return $this->sendResponse(
                null,
                'You may only create one menu',
                ApiCode::BAD_REQUEST,
                0
            );
    }
}
