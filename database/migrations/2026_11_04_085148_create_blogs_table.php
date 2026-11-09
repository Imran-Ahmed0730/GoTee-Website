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
            $table->string('title')->unique();
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->text('thumbnail');
            $table->text('cover_photo')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=>Visible,0=>Hidden');
            $table->timestamps();
            $table->index(['category_id', 'created_at'], 'blogs_category_created_at_index');
            $table->index(['category_id', 'status', 'created_at'], 'blogs_category_status_created_at_index');
            $table->index(['status', 'created_at'], 'blogs_status_created_at_index');
            $table->index('created_at', 'blogs_created_at_index');
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
