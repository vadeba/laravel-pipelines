<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreativityType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
    ];

    public function masterClasses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MasterClass::class, 'type_id');
    }
}
