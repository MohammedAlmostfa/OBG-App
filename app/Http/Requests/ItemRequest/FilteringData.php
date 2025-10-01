<?php

namespace App\Http\Requests\ItemRequest;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FilteringData extends FormRequest
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
    public function rules(): array
    {
    return [
        'category_id'     => 'nullable|integer|exists:categories,id',
        'subCategory_id'  => 'nullable|integer|exists:sub_categories,id',
        'name'            => 'nullable|string|max:255',
        'price'           => 'nullable|numeric|min:0',
        'type'            => 'nullable|string|max:50',
        'status'          => 'nullable|integer|in:0,1',
        'availability'    => 'nullable|integer|in:0,1',
        'lowest'        => 'nullable|boolean',
        'nearest'         => 'nullable|boolean',   // لترتيب حسب الأقرب

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
