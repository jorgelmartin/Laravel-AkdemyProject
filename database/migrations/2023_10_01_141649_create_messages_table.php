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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('convocation_id');
            $table->unsignedBigInteger('user_id');
            $table->string('message');
            $table->date('date');
            
            $table->timestamps();

            $table->foreign('convocation_id')
                ->references('id')
                ->on('convocations')
                ->onDelete('cascade') 
                ->onUpdate('cascade'); 

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade') 
                ->onUpdate('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};