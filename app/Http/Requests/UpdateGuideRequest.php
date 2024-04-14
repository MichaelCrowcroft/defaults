<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGuideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->guide);

    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'body' => ['required', 'string', 'min:10', 'max:12000'],
            'icon' => ['required', 'mimes:jpg,jpeg,png', 'max:1024', 'dimensions:ratio=0/0'],
        ];
    }
}
