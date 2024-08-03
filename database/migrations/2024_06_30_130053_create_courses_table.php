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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('nameCourse');
            $table->integer('coursePeriod');
            $table->decimal('sessionDoration', 5, 1);
            $table->decimal('sessionsGiven', 5, 1)->default(0)->nullable();
            $table->string('type');
            $table->string('courseStatus');
            $table->string('specialty');
            $table->string('description');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
