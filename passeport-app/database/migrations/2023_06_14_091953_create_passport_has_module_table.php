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
        Schema::create('passport_has_module', function (Blueprint $table) {
            $table->id();
            $table->string('description')->default('')->nullable();
            $table->string('choice')->default('');
            $table->string('acronym')->default('');
            $table->date('date')->default(Carbon\Carbon::now());
            $table->string('sign')->default('');
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('passport_id')->references('id')->on('passports')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passport_has_module');
    }
};
