<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('envios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tracking_id');
            $table->text('descripcion')->nullable();
            $table->string('status');
            $table->decimal('peso', 8, 2);
            $table->string('nombre_remitente');
            $table->string('email_remitente')->nullable();
            $table->string('numero_remitente')->nullable();
            $table->string('nombre_receptor')->nullable();
            $table->string('email_receptor')->nullable();
            $table->string('numero_receptor')->nullable();
            $table->string('entrega')->nullable();
            $table->string('departamento')->nullable();
            $table->string('direccion')->nullable();
            $table->string('bodega_etiqueta')->nullable();
            
            // Almacenamos las relaciones como claves foráneas
            $table->unsignedInteger('servicio_id')->nullable();
            $table->unsignedInteger('bodega_id')->nullable();
            $table->unsignedInteger('created_by_id')->nullable();
            $table->unsignedInteger('updated_by_id')->nullable();

            $table->timestamps();

            // Si deseas definir claves foráneas, puedes descomentar estas líneas y ajustarlas según corresponda:
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('set null');
            $table->foreign('bodega_id')->references('id')->on('bodegas')->onDelete('set null');
            $table->foreign('created_by_id')->references('id')->on('usuarios')->onDelete('set null');
            $table->foreign('updated_by_id')->references('id')->on('usuarios')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('envios');
    }
};
