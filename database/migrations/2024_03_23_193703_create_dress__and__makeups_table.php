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
        Schema::create('dress__and__makeups', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->foreignId('parent_id')->nullable()->constrained('dress__and__makeups');
            $table->integer('price');
            $table->string('description')->nullable();
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dress__and__makeups');
    }
};
