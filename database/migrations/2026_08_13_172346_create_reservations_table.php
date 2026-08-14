<?php

use App\Enums\ReservationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId("club_id")->constrained()->cascadeOnDelete();
            $table->foreignId("player_id")->constrained()->cascadeOnDelete();
            $table->date("date");
            $table->time("start_time");
            $table->time("end_time")->nullable();
            $table->unsignedTinyInteger("players_count")->default(1);
            $table->enum("status", array_column(ReservationStatus::cases(), "value"))
                ->default(ReservationStatus::CONFIRMED->value);
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
