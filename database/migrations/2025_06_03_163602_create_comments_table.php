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
    Schema::create('comments', function (Blueprint $table) {
        $table->id();
        
        // Το σχόλιο (κείμενο)
        $table->text('comment');

        // Ο χρήστης που το έκανε (created_by)
        $table->unsignedBigInteger('created_by');
        $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

        // Το post στο οποίο ανήκει
        $table->unsignedBigInteger('post_id');
        $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');

        // Χρονικά πεδία
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
