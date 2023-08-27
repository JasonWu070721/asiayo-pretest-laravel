<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;

use Illuminate\Http\Exceptions\HttpResponseException;

class ConvertCurrencyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'source' => 'string|required|max:3|regex:/^[a-zA-Z]+$/',
            'target' => 'string|required|max:3|regex:/^[a-zA-Z]+$/',
            'amount' => 'string|required|regex:/^\$?\d{1,3}(,\d{3})*(\.\d+)?$/',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($validator->fails()) {
            throw new HttpResponseException(response()->json(
                [
                    'status' => 'error',
                    'msg' => 'Invalid Input',
                    'details' => $validator->errors(),
                ],
                422
            ));
        }
    }
}
