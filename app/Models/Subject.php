<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'major_id',
    ];

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'subject_id');
    }
}
