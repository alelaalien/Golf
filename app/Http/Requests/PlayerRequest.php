<?php

namespace App\Http\Requests;

use App\Enums\PlayerStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlayerRequest extends FormRequest
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
        $id = $this->route("player")?->id;

        return [
             
            'club_id'   => ["sometimes", 'required', 'exists:clubs,id'],
            "name"      => ["sometimes", "required", "string", "max:50"],
            "last_name" => ["sometimes", "required", "string", "max:50"],
            "email"     => ["sometimes", "required", "email", "max:250",
                              Rule::unique("players", "email")->ignore($id)    ],
            "phone"     => ["nullable", "string", "max:50"],
            "handicap"  => ["nullable", "numeric", "between:-10.0, 54.0"],
            "status"    => ["nullable", Rule::enum(PlayerStatus::class)],
 
        ];
    }
}
