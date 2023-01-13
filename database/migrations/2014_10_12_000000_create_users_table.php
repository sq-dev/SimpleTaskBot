<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('users', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->dateTime('last_activity');
            $table->unsignedBigInteger('telegram_id')->unique();
            $table->boolean('notifications')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::dropIfExists('users');
    }
};
