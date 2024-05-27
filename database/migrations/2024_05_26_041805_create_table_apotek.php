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
        Schema::create('apotek', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('pharmacy_license_number');
            $table->string('pharmacy_license_file');
            $table->string('pharmacits_practice_license');
            $table->string('pharmacy_address');
            $table->string('latitut');
            $table->string('longitut');
            $table->boolean('isVerified');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_apotek');
    }
};
