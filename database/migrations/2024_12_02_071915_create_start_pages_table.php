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
        Schema::create('start_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quizz_id')->index();
            $table->text('hero_image')->nullable();
            $table->text('hero_image_mobi')->nullable();
            $table->text('logo')->nullable();
            $table->text('slogan_text')->nullable();
            $table->text('slogan_text_uz')->nullable();
            $table->text('title')->nullable();
            $table->text('title_uz')->nullable();
            $table->text('title_secondary')->nullable();
            $table->text('title_secondary_uz')->nullable();
            $table->text('button_text')->nullable();
            $table->text('button_text_uz')->nullable();
            $table->text('phoneNumber')->nullable();
            $table->text('phoneNumber_uz')->nullable();
            $table->boolean('phoneNumber_type')->default(false);
            $table->text('companyName_text')->nullable();
            $table->text('companyName_text_uz')->nullable();
            $table->integer('design_type')->default(0);
            $table->integer('design_alignment')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('start_pages');
    }
};
