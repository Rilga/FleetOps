<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machinery extends Model
{
    use HasFactory;

    protected $fillable = ['ship_id', 'name', 'maker', 'model', 'serial_number', 'power', 'current_rh'];

    // Relasi balik ke Kapal
    public function ship()
    {
        return $this->belongsTo(Ship::class);
    }

    // Relasi ke Maintenance Task (akan kita buat setelah ini)
    public function maintenanceTasks()
    {
        return $this->hasMany(MaintenanceTask::class);
    }

    /**
     * Dapatkan semua riwayat log harian mesin ini
     */
    public function dailyLogs()
    {
        return $this->hasMany(MachineryDailyLog::class);
    }

    /**
     * Dapatkan log terbaru untuk mengambil 'previous reading' secara otomatis
     */
    public function latestLog()
    {
        return $this->hasOne(MachineryDailyLog::class)->latestOfMany();
    }
}
