<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tratamiento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mascota_id')->constrained('mascota')->onDelete('cascade');
            $table->foreignId('veterinario_id')->constrained('users')->onDelete('cascade');
            $table->string('diagnostico');
            $table->text('observaciones')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tratamiento');
    }
};
