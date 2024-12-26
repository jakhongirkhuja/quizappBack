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
        Schema::create('templetes', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->text('title_uz')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_title_uz')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_description_uz')->nullable();
            $table->text('meta_favicon')->nullable();
            $table->text('meta_image')->nullable();
            $table->text('next_question_text')->nullable();
            $table->text('next_question_text_uz')->nullable();
            $table->text('next_to_form')->nullable();
            $table->text('next_to_form_uz')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templetes');
    }
};
