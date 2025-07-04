<?php

namespace App\Http\Requests\ProfileRequest;

use App\Rules\CheckPhoto;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProfileRequest extends FormRequest
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
            'name' => 'nullable|string|max:255',

            "gender" => 'nullable',
            'birthday' => 'nullable|date|before:-13 years',
            'phone' => 'nullable|regex:/\+963\d{9}/',
            // 'address' => 'nullable|regex:/(^[-0-9A-Za-z.,\/ ]+$)/',
            // 'country_id' => 'nullable|exists:countries,id',
            // 'province_id' => 'nullable|exists:provinces,id',
            'longitude' => 'nullable|',
            'latitude' => 'nullable|',
            'photo' => ['required', 'image', new CheckPhoto]
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
