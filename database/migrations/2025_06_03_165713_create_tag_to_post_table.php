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
        Schema::create('tag_to_post', function (Blueprint $table) {
            $table->id();
    
            $table->unsignedBigInteger('tag_id');
            $table->unsignedBigInteger('post_id');
    
            $table->timestamps();
    
            // Foreign keys
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
    
            // Avoid duplicate tag-post combinations
            $table->unique(['tag_id', 'post_id']);
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_to_post');
    }
};
