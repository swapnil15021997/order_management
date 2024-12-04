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
        Schema::table('user_roles', function (Blueprint $table) {
            $table->string('role_module_ids')->nullable()->after('role_id'); 
            $table->string('role_permission_ids')->nullable()->after('role_module_ids'); 
     
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_roles', function (Blueprint $table) {
            $table->dropColumn('role_module_ids');
            $table->dropColumn('role_permission_ids');
        });
    }
};
