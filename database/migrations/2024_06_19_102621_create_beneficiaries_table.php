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
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->integer('serialNumber')->unique();
            $table->date('date');
            $table->string('province', 100);
            $table->string('name', 50);
            $table->string('fatherName', 50);
            $table->string('motherName', 50);
            $table->string('gender', 20);
            $table->string('dateOfBirth', 100);
            $table->string('nots', 200);
            $table->string('maritalStatus', 100);
            $table->string('needAttendant', 10);
            $table->integer('NumberFamilyMember');
            $table->string('losingBreadwinner', 10);
            $table->string('governorate', 50);
            $table->string('address', 50);
            $table->string('email', 100)->unique();
            $table->string('numberline');
            $table->string('numberPhone');
            $table->string('numberId', 50);
            $table->json('educationalAttainment')->nullable();
            $table->string('computerDriving', 50);
            $table->string('computerSkills', 200);
            $table->string('sectorPreferences');
            $table->string('employment', 200);
            $table->string('supportRequiredTrainingLearning', 500);
            $table->string('supportRequiredEntrepreneurship', 500);
            $table->string('careerGuidanceCounselling', 500);
            $table->string('generalNotes', 500);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};
