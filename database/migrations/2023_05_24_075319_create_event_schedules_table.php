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
        Schema::create('event_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('day', 100);
            $table->time('opening_time');
            $table->time('closing_time');
            $table->date('custom_date')->nullable();
            $table->boolean('custom_repeat')->nullable();
            $table->integer('max_capacity')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'day'], 'unique_event_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_opening_times');
    }
};
