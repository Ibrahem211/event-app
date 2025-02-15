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
        Schema::create('rating_dress_and_makeups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constraiend('users');
            $table->foreignId('dress_and_makeup_id')->constraiend('dress__and__makeups');
            $table->string('rating')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_dress_and_makeups');
    }
};
