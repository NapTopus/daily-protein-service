<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        $item = $this->route('item');
        return $item && $this->user()->can('update', $item);
    }

    public function rules(): array
    {
        return [
            'name'    => 'string|max:255',
            'protein' => 'numeric|gt:0|max:99999.99',
        ];
    }
}
