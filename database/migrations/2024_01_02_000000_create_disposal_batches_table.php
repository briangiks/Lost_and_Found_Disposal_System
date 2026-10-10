<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disposal_batches', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();  // e.g. B-015
            $table->string('cycle_label');           // e.g. "Jan - March"

            // Workflow stage: review -> notice_sent -> pickup_window -> released
            $table->string('stage')->default('review');

            $table->foreignId('officer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('notice_sent_at')->nullable();
            $table->timestamp('handover_due_at')->nullable();  // end of the 7-day pickup window
            $table->timestamp('released_at')->nullable();       // when handed to Community Outreach

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disposal_batches');
    }
};
