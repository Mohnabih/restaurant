<?php

namespace App\Http\Requests\Api;

use App\ApiCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Response;

class BaseRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {
        $response =
            Response::json([
                'status' => ApiCode::BAD_REQUEST,
                'errorCode' => 1,
                'data' => $validator->errors(),
                'message' => "validation error!"
            ], ApiCode::BAD_REQUEST);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
