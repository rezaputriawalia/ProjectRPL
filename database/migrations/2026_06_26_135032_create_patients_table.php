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
        Schema::create('patients', function (Blueprint $table) {

            $table->id();

            $table->string('medical_record_number')->unique();

            // $table->foreignId('doctor_id')
            //     ->constrained('users')
            //     ->cascadeOnUpdate()
            //     ->restrictOnDelete();

            // $table->foreignId('room_id')
            //     ->constrained('rooms')
            //     ->cascadeOnUpdate()
            //     ->restrictOnDelete();

            $table->string('name');

            $table->string('nik')->unique();

            $table->enum('gender', ['L', 'P']);

            $table->date('birth_date');

            $table->text('address');

            $table->string('phone', 20)->nullable();

            // $table->enum('status', [
            //     'rawat_inap',
            //     'rawat_jalan'
            // ])->default('rawat_inap');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
