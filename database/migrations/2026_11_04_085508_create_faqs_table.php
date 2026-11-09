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
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('faq_categories')->onDelete('cascade');
            $table->string('question');
            $table->text('answer');
            $table->tinyInteger('status')->default(0)->comment('0=>Hidden,1=>Visible');
            $table->timestamps();
            $table->index(['category_id', 'created_at'], 'faqs_category_created_at_index');
            $table->index(['category_id', 'status', 'created_at'], 'faqs_category_status_created_at_index');
            $table->index(['status', 'created_at'], 'faqs_status_created_at_index');
            $table->index('created_at', 'faqs_created_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
