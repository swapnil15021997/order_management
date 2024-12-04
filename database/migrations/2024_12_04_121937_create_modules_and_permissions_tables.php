<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesAndPermissionsTables extends Migration
{
    public function up()
    {

        Schema::create('modules', function (Blueprint $table) {
            $table->id('module_id');
            $table->string('module_name');
            $table->boolean('is_delete')->default(false);
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id('permission_id');
            $table->string('permission_name');
            $table->unsignedBigInteger('permission_module_id');
            $table->boolean('permission_status')->default(true);
            $table->boolean('is_delete')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('modules');
    }
}
