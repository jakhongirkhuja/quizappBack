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
        Schema::create('templete_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('templete_questions_id');
            $table->text('image')->nullable();
            $table->text('file')->nullable();
            $table->text('text')->nullable();
            $table->text('text_uz')->nullable();
            $table->text('secondary_text')->nullable();
            $table->text('secondary_text_uz')->nullable();
            $table->timestamp('time_select')->nullable();
            $table->integer('rank')->nullable();
            $table->text('rank_text_min')->nullable();
            $table->text('rank_text_min_uz')->nullable();
            $table->text('rank_text_max')->nullable();
            $table->text('rank_text_max_uz')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templete_answers');
    }
};
