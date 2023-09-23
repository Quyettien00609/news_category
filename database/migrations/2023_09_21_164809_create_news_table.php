<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->text('content');
            $table->string('slug')->unique();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('category_id');
            $table->string('meta_title');
            $table->string('meta_category');
            $table->string('thumbnail_image');
            $table->string('article_image');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
};
