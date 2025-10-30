<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('verificacion', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_verificacion', 8);
            $table->dateTime('expira_en');
            $table->boolean('usado')->default(false);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('verificacion');
    }
};
