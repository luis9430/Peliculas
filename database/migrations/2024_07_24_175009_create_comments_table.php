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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('movie_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->text('comment_text');
            $table->timestamps();

            // Clave foránea hacia la tabla movies
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            // Clave foránea hacia la tabla users (asumiendo que tienes una tabla users)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};



