<?php

namespace App\Http\Requests\Letter;

use App\Models\Letter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLetterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'subject' => ['required', 'string', 'max:255'],
            'sender' => ['required', 'string', 'max:255'],
            'recipient' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in(Letter::statuses())],
            'notes' => ['nullable', 'string'],
        ];
    }
}
