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
        Schema::table('users', function (Blueprint $table) {
            $table->string('surname')->after('name');
            $table->unsignedBigInteger('role_id')->after('password');
            $table->foreign('role_id')->references('id')->on('roles');

            // $table->unsignedBigInteger('convocation_id')->after('role_id');

            // $table->foreign('convocation_id')
            //     ->references('id')
            //     ->on('convocations')
            //     ->onDelete('cascade') 
            //     ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('surname');
            $table->dropColumn('role_id');
        });
    }
};