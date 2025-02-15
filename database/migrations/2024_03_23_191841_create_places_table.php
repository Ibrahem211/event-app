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
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('places');
            $table->foreignId('adress_id')->nullable()->constrained('adresses');
            $table->integer('price')->nullable();
            $table->string('PhoneNumber')->nullable()->unique()->nullable();
            $table->string('description')->nullable();
            $table->string('tele')->nullable()->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
