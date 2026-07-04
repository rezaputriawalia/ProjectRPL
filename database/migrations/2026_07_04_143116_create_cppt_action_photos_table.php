<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cppt_action_photos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('cppt_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('action_name');

            $table->string('category');

            $table->string('photo');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cppt_action_photos');
    }
};