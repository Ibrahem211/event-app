<?php

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
        Schema::create('user_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('event_id')->constrained('events');
            $table->foreignId('place_id')->constrained('places');
            $table->foreignId('decoration_id')->nullable()->constrained('decorations');
            $table->foreignId('food_id')->nullable()->constrained('food');
            $table->foreignId('drees_and_makeup_id')->nullable()->constrained('dress__and__makeups');
            $table->foreignId('songer_id')->nullable()->constrained('songers');
            $table->foreignId('car_id')->nullable()->constrained('cars');
            $table->dateTime('date');
            $table->boolean('photography')->default(0);
            $table->boolean('status');
            $table->boolean('viewability')->default(0);
            $table->boolean('completed')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_events');
    }
};
