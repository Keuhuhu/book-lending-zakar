<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('isbn', 20)->unique()->nullable();
            $table->string('title', 200);
            $table->string('author', 100);
            $table->string('publisher', 100);
            $table->integer('year');
            $table->string('cover_image', 255)->nullable();
            $table->text('description')->nullable();
            $table->integer('stock')->default(0);
            $table->string('location_rack', 20)->nullable();
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};