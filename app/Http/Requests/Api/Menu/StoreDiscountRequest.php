<?php

namespace App\Http\Requests\Api\Menu;

use App\Http\Requests\Api\BaseRequest;
use Illuminate\Validation\Rule;

class StoreDiscountRequest extends BaseRequest
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
            'discountable_type' => [Rule::when(request()->isMethod('POST'), 'required_without:AllMenu'), 'nullable', 'in:Category,Item'],
            'discountable_id' =>  [Rule::when(request()->isMethod('POST'), 'required_without:AllMenu'), 'nullable', 'integer'],
            'amount' => 'required|numeric|between:1,99',
            'AllMenu' => 'required_without:discountable_type|nullable|boolean'
        ];
    }
}
