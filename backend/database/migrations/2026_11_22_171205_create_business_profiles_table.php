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
        Schema::create('business_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('business_id')
                ->unique()
                ->constrained('businesses')
                ->cascadeOnDelete();

            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->json('target_audience')->nullable();
            $table->json('brand_guidelines')->nullable();
            $table->string('brand_voice')->nullable();
            $table->json('marketing_goals')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_profiles');
    }
};
