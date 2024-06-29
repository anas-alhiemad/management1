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
        Schema::create('pending_requests', function (Blueprint $table) {
            $table->id();
            $table->json('requsetPending');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('type');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE pending_requests CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    }

    /**
     *
     * Reverse the migrations.
     *
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_requests');
    }
};
