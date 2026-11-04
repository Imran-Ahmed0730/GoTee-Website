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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('blog_categories')->onDelete('cascade');
            $table->string('title')->unique()->index();
            $table->text('description')->nullable();
            $table->string('slug')->unique()->index();
            $table->text('thumbnail');
            $table->text('cover_photo')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=>Visible,0=>Hidden');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
