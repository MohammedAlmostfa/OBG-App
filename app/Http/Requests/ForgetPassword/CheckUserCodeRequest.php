<?php

namespace App\Http\Requests\ForgetPassword;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Services\ForgetPassword\ForgetPasswordRequestService;

class CheckUserCodeRequest extends FormRequest
{

    protected $forgetPasswordRequestService;
    public function __construct(ForgetPasswordRequestService $forgetPasswordRequestService)
    {
        $this->forgetPasswordRequestService = $forgetPasswordRequestService;
    }
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
            'email' => ['required', 'email', 'exists:users,email'],
            'code' => ['required', 'integer', 'digits:4'],
        ];
    }
    public function attributes(): array
    {
        return  $this->forgetPasswordRequestService->attributes();
    }
    public function failedValidation(Validator $validator)
    {
        $this->forgetPasswordRequestService->failedValidation($validator);
    }
}
