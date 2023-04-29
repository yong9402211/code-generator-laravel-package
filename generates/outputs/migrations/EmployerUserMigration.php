<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employer_users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable()->default(DB::raw('(UUID())'));
            $table->string('work_email', 100);
            $table->string('personal_email', 100)->nullable();
            $table->string('password', 200);
            $table->string('user_role', 200);
            $table->string('role_name', 45);
            $table->string('company_id', 32);
            $table->string('ic_number', 12);
            $table->date('dob')->nullable();
            $table->string('phone_no', 20);
            $table->date('company_start_date')->nullable();
            $table->tinyInteger('first_time_login')->default(0);
            $table->string('position', 45)->nullable();
            $table->string('verification_code', 200);
            $table->string('full_name', 200)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employer_users');
    }
};
