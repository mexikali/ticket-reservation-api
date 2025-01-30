<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('venue_id')->constrained('venues')->onDelete('cascade');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('events');
    }
};
