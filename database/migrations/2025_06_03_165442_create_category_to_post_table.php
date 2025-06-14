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
    Schema::create('category_to_post', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('category_id');
        $table->unsignedBigInteger('post_id');

        $table->timestamps();

        // Foreign keys
        $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');

        // Εναλλακτικά: add unique constraint to avoid duplicates
        $table->unique(['category_id', 'post_id']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_to_post');
    }
};
