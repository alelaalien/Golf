<?php

use App\Enums\PlayerStatus;
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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->cascadeOnDelete();
            $table->string("name");
            $table->string("last_name");
            $table->string("email");
            $table->string("phone");
            $table->decimal("handicap", 4, 1)->default(0.0);
            $table->enum("status", array_column(PlayerStatus::cases(), "value"))
                    ->default(PlayerStatus::ACTIVE->value); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
