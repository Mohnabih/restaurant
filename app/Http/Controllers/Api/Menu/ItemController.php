<?php

namespace App\Http\Controllers\Api\Menu;

use App\ApiCode;
use App\Http\Controllers\Api\AppBaseController;
use App\Http\Requests\Api\Menu\FilterCategoryRequest;
use App\Http\Requests\Api\Menu\StoreItemRequest;
use App\Http\Requests\Api\Menu\UpdateItemRequest;
use App\Models\Menu\Item;
use Illuminate\Support\Facades\Auth;

class ItemController extends AppBaseController
{
    /**
     * Display a list of items.
     *
     * @param  \App\Http\Requests\Api\Menu\FilterCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(FilterCategoryRequest $request)
    {
        $limit = $request->query('limit', 12);
        $type = $request->query('type');
        $user = Auth::user();

        $items = $user->items()
            ->when($type, function ($query) use ($type) {
                $query->$type();
            });
        if ($items->count() > 0)
            return $this->sendResponse(
                $items->paginate($limit),
                'Items retrieved successfully.',
                ApiCode::SUCCESS,
                0
            );
        else
            return $this->sendResponse(
                null,
                'Items not found! ',
                ApiCode::NOT_FOUND,
                1
            );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\Menu\StoreItemRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreItemRequest $request)
    {
        $input = $request->validated();

        $item = Item::create($input);
        return $this->sendResponse(
            $item,
            'Item created successfully.',
            ApiCode::CREATED,
            0
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        if ($item = $user->items()->find($id))
            return $this->sendResponse(
                $item,
                'items retrieved successfully.',
                ApiCode::SUCCESS,
                0
            );
        else
            return $this->sendResponse(
                null,
                'Item not found! ',
                ApiCode::NOT_FOUND,
                1
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\Menu\UpdateItemRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateItemRequest $request, $id)
    {
        $user = Auth::user();
        $input = $request->validated();
        if ($item =  $user->items()->find($id)) {
            $item->update($input);
            return $this->sendResponse(
                $item,
                'Item updated successfully.',
                ApiCode::SUCCESS,
                0
            );
        } else
            return $this->sendResponse(
                null,
                'Item not found! ',
                ApiCode::NOT_FOUND,
                1
            );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if ($item =  $user->items()->find($id)) {
            $item->delete();
            return $this->sendResponse(
                null,
                'Item deleted successfully.',
                ApiCode::SUCCESS,
                0
            );
        } else
            return $this->sendResponse(
                null,
                'Item not found! ',
                ApiCode::NOT_FOUND,
                1
            );
    }
}
