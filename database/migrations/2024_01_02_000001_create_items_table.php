<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // e.g. LF-1042
            $table->string('name');
            $table->string('color')->nullable();
            $table->string('category')->default('Personal');
            $table->date('date_lost');
            $table->string('location');
            $table->text('markings')->nullable();
            $table->string('image_path')->nullable();

            // active = in custody, claimed = collected by owner, donated = handed to Outreach
            $table->string('status')->default('active');

            $table->string('reporter')->nullable(); // staff member who logged the item
            $table->timestamp('claimed_at')->nullable();

            $table->foreignId('disposal_batch_id')->nullable()->constrained('disposal_batches')->nullOnDelete();

            $table->timestamps();

            $table->index(['status', 'date_lost']);
            $table->index('category');
            $table->index('location');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
