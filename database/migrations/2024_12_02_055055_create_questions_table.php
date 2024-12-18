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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->text('front_id')->index();
            $table->unsignedBigInteger('quizz_id')->index();
            $table->text('type');
            $table->text('question');
            $table->boolean('expanded')->default(true);
            $table->boolean('hidden')->default(false);
            $table->integer('order')->default(1);
            $table->text('image')->nullable();
            $table->boolean('self_input')->default(false);
            $table->boolean('expanded_footer')->default(false);
            $table->boolean('multiple_answers')->default(false);
            $table->boolean('required')->default(true);
            $table->boolean('long_text')->default(false);
            $table->integer('proportion')->default(2); // 1 portrait, 2 squere, 3 landscape view of image if it is an image,
            $table->boolean('scroll')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
