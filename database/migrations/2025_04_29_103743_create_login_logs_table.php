<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_logs', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable(); // email attempted
            $table->string('ip_address')->nullable();
            $table->boolean('status')->default(false); // true = success, false = fail
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_logs');
    }
};
