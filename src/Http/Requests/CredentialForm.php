<?php

namespace Webkul\Prestashop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CredentialForm extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'shopUrl' => 'required|unique:wk_prestashop_credentials_config',
            'apiKey'  => 'required',
        ];
    }
}
