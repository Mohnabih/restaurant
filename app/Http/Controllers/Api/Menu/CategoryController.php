<?php

namespace App\Http\Controllers\Api\Menu;

use App\ApiCode;
use App\Http\Controllers\Api\AppBaseController;
use App\Http\Requests\Api\Menu\FilterCategoryRequest;
use App\Http\Requests\Api\Menu\StoreCategoryRequest;
use App\Http\Requests\Api\Menu\UpdateCategoryRequest;
use App\Models\Menu\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends AppBaseController
{
    /**
     * Display a list of main categories.
     *
     * @param  \App\Http\Requests\Api\Menu\FilterCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(FilterCategoryRequest $request)
    {
        $limit = $request->query('limit', 12);
        $status = $request->query('status');
        $user = Auth::user();

        $categories = $user->categories()
            ->parents()
            ->when($status, function ($query) use ($status) {
                $query->$status();
            });
        if ($categories->count() > 0)
            return $this->sendResponse(
                $categories->paginate($limit),
                'Categories retrieved successfully.',
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
     * @param  \App\Http\Requests\Api\Menu\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $input = $request->validated();
        $user = Auth::user();

        $category = $user->categories()->create($input);
        return $this->sendResponse(
            $category,
            'category created successfully.',
            ApiCode::CREATED,
            0
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $category_id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $category_id)
    {
        $limit = $request->query('limit', 12);
        $status = $request->query('status');
        $user = Auth::user();
        $category = Category::find($category_id);
        if ($category) {
            if ($category->type === 0)
                $items = $user->categories()
                    ->where('parent_id', $category_id)
                    ->when($status, function ($query) use ($status) {
                        $query->$status();
                    });
            else
                $items = $category->items()->when($status, function ($query) use ($status) {
                    $query->$status();
                });

            if ($items->count() > 0)
                return $this->sendResponse(
                    $items->paginate($limit),
                    'items retrieved successfully.',
                    ApiCode::SUCCESS,
                    0
                );
            else
                return $this->sendResponse(
                    null,
                    'Items not found!',
                    ApiCode::NOT_FOUND,
                    1
                );
        } else
            return $this->sendResponse(
                null,
                'Category not found!',
                ApiCode::NOT_FOUND,
                1
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\Menu\UpdateCategoryRequest  $request
     * @param  int  $category_id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $category_id)
    {
        $input = $request->validated();
        $user = Auth::user();
        $category = $user->categories()->find($category_id);
        if ($category) {
            $category->update($input);
            return $this->sendResponse(
                $category,
                'category updated successfully.',
                ApiCode::SUCCESS,
                0
            );
        } else
            return $this->sendResponse(
                null,
                'Category not found!',
                ApiCode::NOT_FOUND,
                1
            );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $category_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($category_id)
    {
        $user = Auth::user();
        if ($category = $user->categories()->find($category_id)) {
            $category->delete();
            return $this->sendResponse(
                null,
                'Category deleted successfully.',
                ApiCode::SUCCESS,
                0
            );
        } else
            return $this->sendResponse(
                null,
                'Category not found!',
                ApiCode::NOT_FOUND,
                1
            );
    }
}
