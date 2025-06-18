<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => 'string|max:255',
            'protein' => 'numeric|gt:0|max:99999.99',
            'date'    => 'date',
        ];
    }
}
