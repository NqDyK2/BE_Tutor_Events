<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    const DEFAULT_PAGINATE = 20;
    protected $fillable = [
        'name',
        'user_id',
        'email',
        'subject_id',
        'semester_id',
        'default_online_class_location',
        'default_offline_class_location',
        'default_tutor_email'
    ];
    public function classStudents(){
        return $this->hasMany(ClassStudent::class,'classroom_id');
    }
    public function lessions(){
        return $this->hasMany(Lession::class,'classroom_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
