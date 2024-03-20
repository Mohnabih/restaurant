<?php

namespace App\Http\Controllers\Api\Menu;

use App\ApiCode;
use App\Http\Controllers\Api\AppBaseController;
use App\Http\Requests\Api\Menu\StoreDiscountRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountController extends AppBaseController
{
    /**
     * Display a list of discounts.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 12);
        $user = Auth::user();

        $discounts = $user->discounts();
        if ($discounts->count() > 0)
            return $this->sendResponse(
                $discounts->paginate($limit),
                'Discounts retrieved successfully.',
                ApiCode::SUCCESS,
                0
            );
        else
            return $this->sendResponse(
                null,
                'Discounts not found! ',
                ApiCode::NOT_FOUND,
                1
            );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\Menu\StoreDiscountRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDiscountRequest $request)
    {
        $user = Auth::user();
        $input = $request->validated();

        if ($request->has('AllMenu') && $request->AllMenu) {
            $menu = $user->menu;
            $discount = $menu->discount()->create([
                'user_id' => $user->id,
                'amount' => $input['amount']
            ]);
        } elseif ($input['discountable_type'] == 'Item') {
            if ($item = $user->items()->find($input['discountable_id'])) {
                $discount = $item->discount()->create([
                    'user_id' => $user->id,
                    'amount' => $input['amount']
                ]);
            } else return $this->sendResponse(
                null,
                'This item is not found in this menu',
                ApiCode::NOT_FOUND,
                1
            );
        } else {
            if ($category = $user->categories()->find($input['discountable_id'])) {
                $discount = $category->discount()->create([
                    'user_id' => $user->id,
                    'amount' => $input['amount']
                ]);
            } else {
                return $this->sendResponse(
                    null,
                    'This category is not found in this menu',
                    ApiCode::NOT_FOUND,
                    1
                );
            }
        }
        return $this->sendResponse(
            $discount,
            'Discount created successfully.',
            ApiCode::CREATED,
            0
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\Menu\StoreDiscountRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDiscountRequest $request, $id)
    {
        $user = Auth::user();
        $input = $request->only('amount');
        if ($discount = $user->discounts()->find($id)) {
            $discount->update($input);
            return $this->sendResponse(
                $discount,
                'Discount updated successfully.',
                ApiCode::SUCCESS,
                0
            );
        } else
            return $this->sendResponse(
                null,
                'Discount not found!',
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
        if ($discount =  $user->discounts()->find($id)) {
            $discount->delete();
            return $this->sendResponse(
                null,
                'Discount deleted successfully.',
                ApiCode::SUCCESS,
                0
            );
        } else
            return $this->sendResponse(
                null,
                'Discount not found! ',
                ApiCode::NOT_FOUND,
                1
            );
    }
}
