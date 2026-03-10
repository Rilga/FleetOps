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
        Schema::create('maintenance_histories', function (Blueprint $table) {
            $table->id();
            // Terhubung ke Task tertentu
            $table->foreignId('maintenance_task_id')->constrained()->onDelete('cascade');
            $table->float('done_at_rh'); // Jam mesin saat servis dilakukan
            $table->date('completion_date'); // Tanggal pengerjaan
            $table->text('remarks')->nullable(); // Catatan spesifik pengerjaan ini
            $table->boolean('is_verified')->default(false); // Status verifikasi
            $table->timestamp('verified_at')->nullable();   // Waktu verifikasi
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_histories');
    }
};
