<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitoring_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('monitoring_id')
                ->constrained('monitorings')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->enum('category', [
                'TAK',
                'ADL'
            ]);

            $table->text('action');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring_items');
    }
};