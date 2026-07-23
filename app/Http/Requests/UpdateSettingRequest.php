<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'company_street' => ['required', 'string', 'max:255'],
            'company_city' => ['required', 'string', 'max:255'],
            'company_country' => ['required', 'string', 'max:255'],
            'ico' => ['required', 'string', 'max:255'],
            'dic' => ['required', 'string', 'max:255'],
            'ic_dph' => ['nullable', 'string', 'max:255'],
            'iban' => ['required', 'string', 'max:255'],
            'bic' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'default_currency_code' => ['nullable', 'string', 'max:10'],
        ];
    }
}
