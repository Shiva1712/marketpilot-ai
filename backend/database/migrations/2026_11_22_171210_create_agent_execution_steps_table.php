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
        Schema::create('agent_execution_steps', function (Blueprint $table) {
             $table->id();

            $table->foreignId('execution_id')
                ->constrained('agent_executions')
                ->cascadeOnDelete();

            $table->string('step_name');
            $table->string('status')->default('pending');
            $table->json('result')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_execution_steps');
    }
};
