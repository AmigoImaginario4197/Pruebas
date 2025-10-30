<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('historial_medico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mascota_id')->constrained('mascota')->onDelete('cascade');
            $table->foreignId('tratamiento_id')->nullable()->constrained('tratamiento')->onDelete('set null');
            $table->date('fecha');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('historial_medico');
    }
};
