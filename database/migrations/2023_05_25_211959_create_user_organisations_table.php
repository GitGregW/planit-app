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
        Schema::create('user_organisations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('address_line_1', 100);
            $table->string('address_line_2', 100);
            $table->string('city', 100);
            $table->string('county', 100);
            $table->string('postcode', 10);
            $table->string('contact_mobile', 20);
            $table->string('contact_landline', 20);
            $table->string('email')->unique();
            $table->boolean('verified')->nullable()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_business_details');
    }
};
