<?php

namespace App\Modules\Initiatives\Interfaces\Http\Requests;

use App\Modules\Initiatives\Domain\Enums\InitiativeStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InitiativeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['nullable', Rule::enum(InitiativeStatus::class)],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }
}
