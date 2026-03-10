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
        Schema::create('maintenance_tasks', function (Blueprint $table) {
            $table->id(); // Tambahkan ID primary key jika belum ada
        
            // TAMBAHKAN BARIS INI:
            $table->foreignId('machinery_id')->constrained()->onDelete('cascade');
            
            $table->string('system')->nullable();      // Contoh: Lubrication System
            $table->string('subsystem')->nullable();   // Contoh: Main Engine
            $table->text('job_details');               // Deskripsi detail pekerjaan
            $table->integer('interval');               // Interval Jam (250, 500, dll)
            $table->string('pic');                     // 2E, 3E, CO, dll
            
            // Riwayat Pengerjaan (1st Check - 5th Check di Excel)
            $table->float('check_1_rh')->nullable();
            $table->date('check_1_date')->nullable();
            $table->float('check_2_rh')->nullable();
            $table->date('check_2_date')->nullable();
            $table->float('check_3_rh')->nullable();
            $table->date('check_3_date')->nullable();
            $table->float('check_4_rh')->nullable();
            $table->date('check_4_date')->nullable();
            $table->float('check_5_rh')->nullable();
            $table->date('check_5_date')->nullable();

            // Kolom Kalkulasi Utama
            $table->float('last_done_rh')->default(0); 
            $table->float('next_due_rh');              
            $table->string('status')->default('normal'); // normal, warning, critical
            
            $table->text('remarks')->nullable();       // Catatan pengerjaan terakhir
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_tasks');
    }
};
