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
        Schema::create('case_studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('testimonial_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title')->index();
            $table->string('industry')->index();
            $table->string('location');
            $table->text('challenge');
            $table->text('solution');
            $table->text('results');
            $table->tinyInteger('status')->default(0)->comment('1=> Active, 0=> Inactive')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_studies');
    }
};
