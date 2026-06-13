<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterClass extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'master_id', 'type_id', 'title', 'description', 'date', 'time_slot', 'max_seats', 'price',
    ];

    public function master()
    {
        return $this->belongsTo(User::class, 'master_id');
    }

    public function type()
    {
        return $this->belongsTo(CreativityType::class, 'type_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'master_class_id', 'user_id')->withTimestamps();
    }

    public function getFreeSeatsAttribute()
    {
        return $this->max_seats - $this->participants()->count();
    }
}
