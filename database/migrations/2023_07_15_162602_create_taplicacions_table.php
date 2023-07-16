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
            $table->string('titulo', 200);
            $table->string('autor', 254);
            $table->unsignedBigInteger('pestudio_id');
            $table->string('resumen', 1500);
            $table->string('archivo', 254);
            $table->timestamps();

            $table->foreign('pestudio_id')
                ->references('id')
                ->on('pestudios')
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
