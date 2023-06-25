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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("cid");
            $table->string("bitrix_id")->nullable();
            $table->string("username")->nullable();
            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
            $table->string("phone")->nullable();
            $table->string("cookie")->default("null");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
