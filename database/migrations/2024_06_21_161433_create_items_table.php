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
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id'); // unsignedBigInteger
            $table->string('name')->default('null');
            $table->string('description')->default('null');
            $table->integer('quantity')->default(0);
            $table->boolean('status')->default(true);// if expierd_date pass the Item 
            $table->boolean('available')->default(false); // if manager accept the Item requiest
            $table->date('expierd_date')->nullable();
    
            $table->foreignId('type_id')->constrained('types'); // Foreign key to types table
            $table->foreignId('category_id')->constrained('categories'); // Foreign key to categories table
            
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
