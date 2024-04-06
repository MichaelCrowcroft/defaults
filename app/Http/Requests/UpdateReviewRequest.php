<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->review);
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:2500'],
            'stars' => ['required', 'integer', 'max:5', 'min:1'],
        ];
    }
}
