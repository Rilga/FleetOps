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
        Schema::create('machinery_daily_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machinery_id')->constrained();
            $table->date('log_date');
            $table->float('previous_reading'); // Reading kemarin
            $table->float('current_reading');  // Reading hari ini (Input harian)
            $table->float('consumption')->storedAs('current_reading - previous_reading');
            $table->boolean('is_breakdown')->default(false); // Toggle Normal/Breakdown
            $table->text('remarks')->nullable();
            $table->foreignId('user_id')->constrained(); // PIC yang input
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machinery_daily_logs');
    }
};
