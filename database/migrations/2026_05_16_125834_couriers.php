<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('couriers', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('name');
            $table->string('nik')->unique();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->string('status')->nullable();
            $table->string('level')->nullable();
            $table->timestamps(); // created_at & updated_at
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('couriers');
    }
};