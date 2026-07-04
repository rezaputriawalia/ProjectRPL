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
        Schema::create('cppts', function (Blueprint $table) {

            $table->id();

            $table->foreignId('registration_id')
                ->constrained('registrations')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('doctor_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('nurse_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->longText('subjective');

            $table->longText('objective');

            $table->longText('assessment');

            $table->longText('plan');

            $table->json('selected_actions')->nullable();

            $table->text('monitoring_note')->nullable();

            $table->string('photo')->nullable();

            $table->enum('verification_status', [
                'pending',
                'verified'
            ])->default('pending');

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cppts');
    }
};
