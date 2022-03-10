<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
// use App\Exceptions\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

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
        return [
            'location' => ['required'],
            'radius' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'location.required' => 'Location attribute is missing.',
            'radius.required' => 'Radius attribute is missing.'
        ];
    }

    // public function validate(): void
    // {
    //     if ($this->request->location === null) {
    //         $error = 'Location field is emty';
    //     } else if ($this->request->radius === null) {
    //         $error = 'Radius field is empty.';
    //     }

    //     if (isset($error)) {
    //         throw new ValidationException($error);
    //     }
    // }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response(['success' => false, 'error' => $errors], 400)
        );
    }
}
