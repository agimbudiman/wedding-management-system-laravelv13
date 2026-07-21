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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('event_categories')->onDelete('cascade');
            $table->string('name', 50);
            $table->string('client_name', 50);
            $table->text('client_address')->nullable();
            $table->date('date');
            $table->string('venue', 150);
            $table->string('type', 50); // e.g., Wedding, Birthday
            $table->decimal('budget_estimate', 15, 2)->nullable();
            $table->string('status', 20)->default('Upcoming'); // Upcoming, In Progress, Completed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
