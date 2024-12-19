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
        Schema::create('quizzs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->text('front_id');
            $table->text('title')->nullable();
            $table->text('title_uz')->nullable();
            $table->unsignedBigInteger('user_id')->index();
            $table->text('url')->unique();
            $table->unsignedBigInteger('project_id')->index();
            $table->boolean('domainType')->default(false); //false= Стандартная, true= На поддомене
            $table->boolean('publish')->default(false);
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
            $table->boolean('startPage')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzs');
    }
};
