<?php

namespace App\Http\Requests;

use App\Http\Responses\Response;
use \Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;


class UserSignUpRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|min:4',
            'password' => 'required|confirmed|min:8'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        // throw a validationException with the translated error messages
        throw new ValidationException($validator, Response::Validation([], $validator->errors()));
    }
}
