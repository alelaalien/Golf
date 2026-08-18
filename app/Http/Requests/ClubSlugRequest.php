<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClubSlugRequest extends FormRequest
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
        return [
            
            "slug" =>
            [
                "required",
                "string",
                "max:250",
                "regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/",
                Rule::unique("clubs", "slug")->ignore($this->route("club"))
            ]
        ];
    }
}
