<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        // TODO: add validation for , and .
        return [
            'location' => ['required'],
            'radius' => ['required'],
            'limit' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'location.required' => 'Location attribute is missing.',
            'radius.required' => 'Radius attribute is missing.',
            'limit' => 'Limit attribute is missing.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $formattedErrors = [];
        $errorInfo = (new ValidationException($validator))->errors();

        foreach ($errorInfo as $errors) {
            foreach ($errors as $error) {
                $formattedErrors[] = $error;
            }
        }

        throw new HttpResponseException(
            response(['success' => false, 'errors' => $formattedErrors], 400)
        );
    }
}
