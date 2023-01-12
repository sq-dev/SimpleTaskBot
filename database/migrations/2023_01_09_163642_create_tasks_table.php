<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tasks', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('completed')->default(false);
            $table->string('description')->nullable();
            $table->enum('type', ['daily', 'simple'])->default('simple');
            $table->string('deadline')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void {
        Schema::dropIfExists('tasks');
    }
};
