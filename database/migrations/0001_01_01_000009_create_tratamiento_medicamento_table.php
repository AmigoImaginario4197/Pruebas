<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tratamiento_medicamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tratamiento_id')->constrained('tratamiento')->onDelete('cascade');
            $table->foreignId('medicamento_id')->constrained('medicamento')->onDelete('cascade');
            $table->string('frecuencia')->nullable();
            $table->string('duracion')->nullable();
            $table->string('dosis')->nullable();
            $table->text('instrucciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tratamiento_medicamento');
    }
};
