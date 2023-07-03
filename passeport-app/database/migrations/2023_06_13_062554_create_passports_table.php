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
        Schema::create('passports', function (Blueprint $table) {
            // $table->id();
            $table->uuid('id')->primary()->unique();
            $table->string('class')->default('');
            $table->string('student_choice')->default('');
            $table->string('motivation')->default('');
            $table->double('first_note')->default(1);
            $table->double('second_note')->default(1);
            $table->double('third_note')->default(1);
            $table->date('student_date')->default(Carbon\Carbon::now());
            $table->string('student_sign')->default('');
            $table->string('legal_comment')->default('');
            $table->date('legal_date')->default(Carbon\Carbon::now());
            $table->string('legal_sign')->default('');
            $table->foreignId('user_id')->constrained('users');
            $table->string('confirmation_token')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passports');
    }
};
