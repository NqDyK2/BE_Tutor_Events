<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    const TRASH_EXPIRED_DAYS = 30;

    protected $fillable = [
        'name',
        'content',
        'image',
        'status',
        'location',
        'start_time',
        'end_time',
        'trashed_at'
    ];
    
    public function eventUsers()
    {
        return $this->hasMany(EventUser::class, 'event_id');
    }
}
