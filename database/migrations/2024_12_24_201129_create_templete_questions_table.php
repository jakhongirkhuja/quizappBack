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
        Schema::create('templete_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('templete_id')->index();
            $table->text('type');
            $table->text('question');
            $table->text('question_uz')->nullable();
            $table->text('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templete_questions');
    }
};
