<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeviceTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_token' => [
                'required',
                'string',
                'max:2048',
            ],

            'device_type' => [
                'nullable',
                'string',
                'in:android,ios',
            ],
        ];
    }
}