<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('agenda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mascota_id')->nullable()->constrained('mascota')->onDelete('cascade');
            $table->date('fecha');
            $table->string('actividad');
            $table->text('observaciones')->nullable();
            $table->time('hora_inicio');
            $table->time('hora_fin')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('agenda');
    }
};
