<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id(); // id auto increment
            $table->string('title', 255);
            $table->text('content');
            $table->foreignId('author')
                  ->constrained('users')
                  ->onDelete('cascade'); // ή restrict ανάλογα την πολιτική σου
            $table->string('slug')->unique();
            $table->timestamps(); // created_at & updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
