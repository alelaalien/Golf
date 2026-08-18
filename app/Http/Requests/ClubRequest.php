<?php

namespace App\Http\Requests;

use App\Enums\ClubStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClubRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $clubId = $this->route('club')?->id;
       return [
        "name"          => ["required", "string", "max:250"], 
        "slug"          => [
            "sometimes", 
            "required", 
            "string", 
            "max:250",
            Rule::unique('clubs', 'slug')->ignore($clubId)
        ],
        "email"         => [
            "sometimes",
            "required", 
            "email", 
            Rule::unique('clubs', 'email')->ignore($clubId)
        ],
        "phone"         => ["nullable", "string", "max:20"],
        "status"        => ["nullable", Rule::enum(ClubStatus::class)],
        "configuration" => ["nullable", "array"],
        "address"       => ["nullable", "string", "max:250"],
    ];
    }
}
