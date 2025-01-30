<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained('venues')->onDelete('cascade');
            $table->string('section')->nullable();
            $table->string('row');
            $table->integer('number');
            $table->enum('status', ['available', 'reserved', 'sold'])->default('available');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('seats');
    }
};
