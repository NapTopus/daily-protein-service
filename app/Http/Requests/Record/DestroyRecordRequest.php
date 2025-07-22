<?php

namespace App\Http\Requests\Record;

use Illuminate\Foundation\Http\FormRequest;

class DestroyRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $record = $this->route('record');
        return $record && $this->user()->can('delete', $record);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
