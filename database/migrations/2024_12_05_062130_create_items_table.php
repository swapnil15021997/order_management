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
        Schema::create('items', function (Blueprint $table) {
            $table->id('item_id'); 
            $table->string('item_metal',256)->comment('Metal of item'); 
            $table->string('item_name',256)->comment('Name of item');;
            $table->string('item_melting',256);
            $table->integer('item_weight')->comment("Weight in grams");
            $table->string('item_file_images')->nullable()->comment("Comma separated files");
            $table->boolean('is_delete')->default(false);
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
