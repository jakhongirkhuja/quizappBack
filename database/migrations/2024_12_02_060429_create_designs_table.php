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
        Schema::create('designs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quizz_id')->index();
            $table->integer('design_id')->nullable();
            $table->string('designTitle')->nullable();
            $table->string('buttonColor')->nullable();
            $table->string('buttonTextColor')->nullable();
            $table->string('textColor')->nullable();
            $table->string('bgColor')->nullable();
            $table->integer('buttonStyle')->default(0);
            $table->integer('progressBarStyle')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designs');
    }
};
