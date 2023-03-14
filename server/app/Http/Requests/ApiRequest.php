<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\{CurrencyName, CurrencyOperation, GpsFormat};

class ApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        // TODO: add validation for , and .
        return [
            'location' => ['required', 'string', new GpsFormat],
            'radius' => ['required', 'integer', 'numeric'],
            'limit' => ['required', 'integer', 'numeric'],
            'currency' => ['required', 'string', new CurrencyName],
            'operationType' => ['required', 'string', new CurrencyOperation],
        ];
    }

    public function messages(): array
    {
        return [
            'location.required' => 'Location attribute is missing.',
            'radius.required' => 'Radius attribute is missing.',
            'limit.required' => 'Limit attribute is missing.',
            'currency.required' => 'Currency attribute is missing.',
            'operationType.required' => 'operationType attribute is missing.',
            'location.string' => 'Location attribute must be string.',
            'currency.string' => 'Currency attribute must be string.',
            'operationType.string' => 'operationType must be string.',
            'radius.integer' => 'Radius attribute must be integer.',
            'limit.integer' => 'Limit attribute must be integer.'
        ];
    }

    protected function failedValidation(Validator $validator): void
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
