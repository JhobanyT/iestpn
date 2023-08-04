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
        Schema::create('trabajo_autors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trabajo_id'); // Relación con el trabajo de aplicación
            $table->unsignedBigInteger('autor_id'); // Relación con el autor
            $table->timestamps();

            $table->foreign('trabajo_id')
                ->references('id')
                ->on('taplicacions')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('autor_id')
                ->references('id')
                ->on('autors')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                });
            }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabajo_autors');
    }
};
