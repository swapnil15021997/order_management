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
        Schema::create('metals', function (Blueprint $table) {
            $table->id('metal_id'); 
            $table->string('metal_name')->comment('Metals Name');
            $table->boolean('is_delete')->default(false);
            $table->timestamps(); 
        });

        Schema::create('melting', function (Blueprint $table) {
            $table->id('melting_id'); 
            $table->string('melting_name')->comment('Metaling');
            $table->boolean('is_delete')->default(false);
            $table->timestamps(); 
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metals');
        Schema::dropIfExists('melting');
    }
};
