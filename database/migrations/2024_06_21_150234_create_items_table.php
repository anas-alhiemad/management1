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
            $table->boolean('status')->default(true);
            $table->boolean('available')->default(false); // if manager accept the Item requiest
            $table->date('expierd_date')->nullable();
    
            $table->integer('type_id');
         //   $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');

         $table->integer('categorie_id');
      //   $table->foreign('categories_id')->references('id')->on('categories')->cascadeOnDelete();
        
            
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
