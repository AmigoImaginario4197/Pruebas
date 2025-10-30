<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('plan_diario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mascota_id')->constrained('mascota')->onDelete('cascade');
            $table->date('fecha');
            $table->string('actividad');
            $table->string('hora')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('plan_diario');
    }
};
