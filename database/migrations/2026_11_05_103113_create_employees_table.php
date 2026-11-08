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
            $table->string('employee_id')->unique();
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('phone');
            $table->tinyInteger('gender')->comment('1=Male, 2=Female, 3=Other');
            $table->foreignId('blood_group_id')->nullable()->constrained('blood_groups')->onDelete('set null');
            $table->text('present_address');
            $table->text('permanent_address');
            $table->string('nid_no')->nullable()->unique();
            $table->string('nid_image')->nullable();

            $table->string('contacting_person_first_name');
            $table->string('contacting_person_last_name')->nullable();
            $table->string('contacting_person_phone');
            $table->string('contacting_person_relation');
            $table->text('contacting_person_address')->nullable();

            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->foreignId('designation_id')->nullable()->constrained('designations')->onDelete('set null');
            $table->foreignId('reporting_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('id_card_no')->nullable()->unique();
            $table->date('joining_date');
            $table->decimal('salary', 10, 2)->default(0);
            $table->text('note')->nullable();

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
