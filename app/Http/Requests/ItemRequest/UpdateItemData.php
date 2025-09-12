<?php

namespace App\Http\Requests\ItemRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateItemData extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|integer|exists:categories,id',
            'type' => 'nullable|in:fixed,negotiable',
            'subCategory_id' => 'nullable|integer|exists:sub_categories,id',
            'description' => 'nullable|string',

        ];
    }
    /**
     * Handle a failed validation attempt.
     * This method is called when validation fails.
     * Logs failed attempts and throws validation exception.
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     *
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'status'  => 'error',
            'message' => 'Validation failed.',
            'errors'  => $validator->errors(),
        ], 422));
    }
}
