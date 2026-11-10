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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('image');
            $table->tinyInteger('position')->default(1)->comment('1=Top, 2=Middle, 3=Bottom');
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->timestamps();
            $table->index(['position', 'status', 'created_at'], 'banners_position_status_created_at_index');
            $table->index(['position', 'created_at'], 'banners_position_created_at_index');
            $table->index(['status', 'created_at'], 'banners_status_created_at_index');
            $table->index('created_at', 'banners_created_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
