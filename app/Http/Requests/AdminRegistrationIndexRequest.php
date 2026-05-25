<?php

namespace App\Http\Requests;

use App\Models\Activity;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRegistrationIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:120'],
            'cities' => ['nullable', 'array'],
            'cities.*' => ['string', 'max:120'],
            'period_names' => ['nullable', 'array'],
            'period_names.*' => ['string', 'max:255'],
            'age_groups' => ['nullable', 'array'],
            'age_groups.*' => ['string', Rule::in(Activity::ageGroupKeys())],
        ];
    }
}
