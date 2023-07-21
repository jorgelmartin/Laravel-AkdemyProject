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
        Schema::create('user_convocation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('convocation_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('status')->default(false);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('convocation_id')->references('id')->on('convocations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_convocation');
    }
};
