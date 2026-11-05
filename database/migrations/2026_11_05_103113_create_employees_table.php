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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('phone');
            $table->text('address');
            $table->tinyInteger('gender')->comment('1=Male, 2=Female, 3=Other');
            $table->foreignId('blood_group_id')->nullable()->constrained('blood_groups')->onDelete('set null');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->foreignId('designation_id')->nullable()->constrained('designations')->onDelete('set null');
            $table->string('nid_no')->nullable()->unique();
            $table->string('nid_image')->nullable();
            $table->date('joining_date');
            $table->decimal('salary', 10, 2)->default(0);
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->tinyInteger('show_as_team')->default(0)->comment('1=Show as team member, 0=Do not show');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
