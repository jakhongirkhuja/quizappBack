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
        Schema::create('form_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quizz_id')->index();
            $table->text('hero_image')->nullable();
            $table->text('hero_image_mobi')->nullable();
            $table->text('title')->nullable();
            $table->text('title_uz')->nullable();
            $table->text('title_secondary')->nullable();
            $table->text('title_secondary_uz')->nullable();
            $table->text('button_text')->nullable();
            $table->text('button_text_uz')->nullable();
            $table->boolean('name')->default(true);
            $table->boolean('email')->default(true);
            $table->boolean('phone')->default(true);
            $table->boolean('name_required')->default(true);
            $table->boolean('email_required')->default(false);
            $table->boolean('phone_required')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_pages');
    }
};
