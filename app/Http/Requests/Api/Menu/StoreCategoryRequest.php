<?php

namespace App\Http\Requests\Api\Menu;

use App\Http\Requests\Api\BaseRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $userCatIDs = Auth::user()->categories()->where('type', 1)->pluck('id')->toArray();
        return [
            'parent_id' => ['nullable', 'integer', Rule::in($userCatIDs)],
            'name' => 'required|string|min:2|max:255',
            'description' => 'nullable|string|min:10',
            'type' => 'required|in:0,1',
        ];
    }
}
