<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalReviewsTable extends Migration
{
    public function up(): void
    {
        Schema::create('external_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->string('author_name');
            $table->string('author_url')->nullable();
            $table->string('profile_photo_url')->nullable();
            $table->tinyInteger('rating');
            $table->text('text')->nullable();
            $table->string('relative_time_description')->nullable();
            $table->string('source')->default('google'); // en caso de obtener reviews de otras fuentes en el futuro
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_reviews');
    }
}
