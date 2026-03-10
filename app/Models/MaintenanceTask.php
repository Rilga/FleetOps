<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceTask extends Model
{
    protected $fillable = [
        'machinery_id', 'system', 'subsystem', 'job_details', 'interval', 'pic',
        'check_1_rh', 'check_1_date', 'check_2_rh', 'check_2_date', 
        'check_3_rh', 'check_3_date', 'check_4_rh', 'check_4_date', 
        'check_5_rh', 'check_5_date',
        'last_done_rh', 'next_due_rh', 'status', 'remarks'
    ];

    public function machinery()
    {
        return $this->belongsTo(Machinery::class);
    }

    public function histories() {
        return $this->hasMany(MaintenanceHistory::class)->latest();
    }
}
