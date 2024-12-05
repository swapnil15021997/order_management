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
        Schema::create('files', function (Blueprint $table) {
            $table->id('file_id'); 
            $table->string('file_name')->comment('System-generated name for the file');
            $table->string('file_original_name')->comment('Original name of the file uploaded');
            $table->string('file_path')->comment('Path to the stored file');
            $table->string('file_url')->comment('Public URL of the file');
            $table->string('file_type', 50)->comment('MIME type of the file, e.g., image/jpeg');
            $table->unsignedBigInteger('file_size')->comment('File size in bytes');
            $table->boolean('is_delete')->default(false)->comment('Soft delete flag');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
