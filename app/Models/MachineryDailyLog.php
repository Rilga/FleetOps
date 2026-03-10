<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MachineryDailyLog extends Model
{
    protected $table = 'machinery_daily_logs';

    protected $fillable = [
        'machinery_id',
        'log_date',
        'previous_reading',
        'current_reading',
        'is_breakdown',
        'remarks',
        'user_id',
    ];

    /**
     * Hubungkan Log ini ke Mesinnya
     */
    public function machinery(): BelongsTo
    {
        return $this->belongsTo(Machinery::class);
    }

    /**
     * Hubungkan Log ini ke User (Engineer) yang menginput
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
