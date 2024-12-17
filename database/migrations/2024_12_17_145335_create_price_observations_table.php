<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_observations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users')->constrained()->cascadeOnDelete();
            $table->foreignId('assets')->constrained()->cascadeOnDelete();
            $table->double('target')->nullable(false);
            $table->boolean('active')->default(true)->nullable(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_observations');
    }
};
