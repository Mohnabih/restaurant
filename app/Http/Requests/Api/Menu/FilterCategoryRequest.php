<?php

namespace App\Http\Requests\Api\Menu;

use App\Http\Requests\Api\BaseRequest;
use App\Models\Menu\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FilterCategoryRequest extends BaseRequest
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
        return [
            'status' => 'nullable|string|in:available,unavailable',
        ];
    }
}
