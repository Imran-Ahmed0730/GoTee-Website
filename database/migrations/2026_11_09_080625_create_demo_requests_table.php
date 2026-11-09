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
        Schema::create('demo_requests', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->string('contacting_person_first_name');
            $table->string('contacting_person_last_name')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->integer('machine_qty');
            $table->tinyInteger('status')->default(0)->comment('0=> Pending, 1=>Contacted, 2=>Completed, 3=>Cancelled');
            $table->timestamps();
            $table->index(['created_at', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demo_requests');
    }
};
