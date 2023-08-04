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
        Schema::create('taplicacions', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 254);
            $table->text('resumen');
            $table->unsignedBigInteger('user_id');
            $table->enum('tipo', ['Normal', 'Interdisciplinario']); // Campo para indicar si es "normal" o "interdisciplinario"
            $table->string('archivo', 254)->unique();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taplicacions');
    }
};
