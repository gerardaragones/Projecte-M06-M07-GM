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
        Schema::create('favorites', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                  ->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('place');
            $table->foreign('place')->references('id')->on('places')
                  ->onUpdate('cascade')->onDelete('cascade');
            // Eloquent does not support composite PK :-(
            // $table->primary(['user_id', 'place']);
        });
        // Eloquent compatibility workaround :-)
        Schema::table('favorites', function (Blueprint $table) {
            $table->id()->first();
            $table->unique(['user_id', 'place']);
        });
 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};