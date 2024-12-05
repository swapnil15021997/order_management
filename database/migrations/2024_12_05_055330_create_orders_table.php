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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->date('order_date');
            $table->string('order_number')->unique()->comment('Order number');
            $table->string('order_qr_code')->unique()->comment('Order QR Number');
            $table->unsignedBigInteger('order_from_branch_id')->nullable()->comment('Order From Branch ID');
            $table->unsignedBigInteger('order_to_branch_id')->nullable()->comment('Order To Branch ID');
            $table->unsignedBigInteger('order_user_id')->nullable()->comment('User ID');
            $table->unsignedBigInteger('order_type')->comment('1=> Order , 2=> Repairing');
            $table->boolean('is_delete')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
