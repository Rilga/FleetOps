<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceHistory extends Model
{
    protected $fillable = 
    ['maintenance_task_id', 
    'done_at_rh', 
    'completion_date', 
    'remarks',
    'is_verified', 
    'verified_at', 
    'verified_by'];

    public function task() {
        return $this->belongsTo(MaintenanceTask::class, 'maintenance_task_id');
    }
    
    public function verifier() {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
