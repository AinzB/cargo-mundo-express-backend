<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('bodegas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo')->unique();
            $table->boolean('active')->default(true);
            // Almacena la información JSON que se genera desde Angular
            $table->json('dataJson')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('bodegas');
    }
};
