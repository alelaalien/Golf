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
        $isUpdate = $this->route('player') !== null;

    return [
        'club_id'   => [$isUpdate ? 'sometimes' : 'required', 'exists:clubs,id'],
        "name"      => [$isUpdate ? 'sometimes' : 'required', "string", "max:50"],
        "last_name" => [$isUpdate ? 'sometimes' : 'required', "string", "max:50"],
        "email"     => [$isUpdate ? 'sometimes' : 'required', "email", "max:250",
            Rule::unique('players', 'email')->ignore($this->route('player')),
        ],
        "phone"     => ["nullable", "string", "max:50"],
        "handicap"  => ["nullable", "numeric", "between:-10.0,54.0"],
        "status"    => ["nullable", Rule::enum(PlayerStatus::class)],
    ];
    }
}
