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
       //     $table->json('thereIsDisbility')->nullable();
            $table->string('needAttendant', 10);
            $table->integer('NumberFamilyMember');
       //     $table->json('thereIsDisbilityFamilyMember')->nullable();
            $table->string('losingBreadwinner', 10);
            $table->string('governorate', 50);
            $table->string('address', 50);
            $table->string('email', 100)->unique();
            $table->integer('numberline');
            $table->integer('numberPhone');
            $table->string('numberId', 50);
            $table->json('educationalAttainment')->nullable();
        //   $table->json('previousTrainingCourses')->nullable();
        //    $table->json('foreignLanguages')->nullable();
            $table->string('computerDriving', 50);
            $table->string('computerSkills', 200);
        //    $table->json('professionalSkills')->nullable();
            $table->string('sectorPreferences');
            $table->string('employment', 200);
            $table->string('supportRequiredTrainingLearning', 500);
            $table->string('supportRequiredEntrepreneurship', 500);
            $table->string('careerGuidanceCounselling', 500);
            $table->string('generalNotes', 500);
            $table->timestamps();
        });

        // Schema::create('beneficiaries', function (Blueprint $table) {
        //     $table->id();
        //     $table->integer('serialnumber')->unique();
        //     $table->date('date');
        //     $table->string('province');
        //     $table->string('name');
        //     $table->string('fatherName');
        //     $table->string('matherName');
        //     $table->string('gender');
        //     $table->string('dateOfBirth');
        //     $table->string('nots');
        //     $table->string('maritalStatus');
        //     $table->string('thereIsDisbility')->nullable();   //////
        //     $table->string('needAttendant');
        //     $table->integer('NumberFamilyMember');
        //     $table->string('thereIsDisbilityFamilyMember');
        //     $table->string('losingBreadwinner');
        //     $table->string('governorate');
        //     $table->string('address');
        //     $table->string('email');
        //     $table->string('numberline');
        //     $table->string('phone');
        //     $table->string('numberId');
        //     $table->string('educationalAttainment');
        //     $table->string('previousTrainingCourses');
        //     $table->string('foreignLanguages');
        //     $table->string('computerDriving');
        //     $table->string('computerSkills');
        //     $table->string('professionalSkills');
        //     $table->string('sectorPreferences');
        //     $table->string('employment');
        //     $table->string('supportRequiredTrainingLearning');
        //     $table->string('supportRequiredEntrepreneurship');
        //     $table->string('careerGuidanceCounselling');
        //     $table->string('generalNotes');

        //     $table->timestamps();
        //});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};
