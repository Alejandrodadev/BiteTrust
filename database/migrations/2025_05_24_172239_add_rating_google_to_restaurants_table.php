<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('restaurants', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->decimal('ratingGoogle', 3, 1)->nullable()->after('google_place_id');
        });
    }

    public function down()
    {
        Schema::table('restaurants', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('ratingGoogle');
        });
    }
};
