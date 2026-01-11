<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardLog extends Model
{
    use HasFactory;

    protected $primaryKey = 'log_id';
    
    protected $fillable = [
        'user_id',
        'activity',
        'log_date',
    ];

    protected $casts = [
        'log_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
