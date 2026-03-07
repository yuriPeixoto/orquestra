<?php

namespace App\Modules\Decisions\Interfaces\Http\Requests;

use App\Modules\Decisions\Domain\Enums\DecisionStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DecisionRequest extends FormRequest
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
            'context' => ['required', 'string', 'max:10000'],
            'decision' => ['required', 'string', 'max:10000'],
            'consequences' => ['nullable', 'string', 'max:10000'],
            'status' => ['nullable', Rule::enum(DecisionStatus::class)],
            'initiative_id' => ['nullable', 'integer', 'exists:initiatives,id'],
        ];
    }
}
