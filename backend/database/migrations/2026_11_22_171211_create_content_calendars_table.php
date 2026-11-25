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
        Schema::create('content_calendars', function (Blueprint $table) {
            $table->id();

            $table->foreignId('content_post_id')
                ->constrained('content_posts')
                ->cascadeOnDelete();

            $table->foreignId('social_account_id')
                ->constrained('social_accounts')
                ->cascadeOnDelete();

            $table->dateTime('scheduled_at');
            $table->string('publishing_status')->default('scheduled');

            $table->timestamps();

            $table->index(['scheduled_at', 'publishing_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_calendars');
    }
};
