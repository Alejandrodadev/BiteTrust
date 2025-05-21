<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('review_photos', function (Blueprint $table) {
            // Añadimos la columna original_url, nullable para que los registros existentes no fallen
            $table->string('original_url')->nullable()->after('photo_url');
        });
    }

    public function down(): void
    {
        Schema::table('review_photos', function (Blueprint $table) {
            // En caso de rollback eliminamos la columna
            $table->dropColumn('original_url');
        });
    }
};
