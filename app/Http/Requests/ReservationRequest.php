<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
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
                "player_id"         => ["required", "integer", "exists:players,id"],
                "date"              => ["required", "date"],
                "start_time"        => ["required", "date_format:H:i"],
                "end_time"          => ["required", "date_format:H:i", "after:start_time"], 
                "players_count"     => ["required", "integer", "min:1"],
                "status"            => ["required", "string", "max:20"],
                "club_id"           => ["required", "integer", "exists:clubs,id"],
        ];
    }
}
