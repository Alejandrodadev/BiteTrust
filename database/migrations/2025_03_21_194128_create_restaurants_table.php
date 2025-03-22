<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('city', 100);
            $table->string('country', 100);
            $table->string('google_place_id')->unique();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
