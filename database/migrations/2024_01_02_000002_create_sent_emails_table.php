<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sent_emails', function (Blueprint $table) {
            $table->id();
            // comms_notice | student_notice | outreach_handover
            $table->string('type');
            $table->string('from_address');
            $table->string('to_address');
            $table->string('cc_address')->nullable();
            $table->string('subject');
            $table->longText('body_html');
            $table->foreignId('disposal_batch_id')->nullable()->constrained('disposal_batches')->nullOnDelete();
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sent_emails');
    }
};
