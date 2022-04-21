<?php

namespace App\Models;

use App\Models\Teacher;
use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;
    use TraitUuid;

    protected $fillable = [
        'name', 'agency'
    ];

    public $incrementing = false;

    public function teacher()
    {
        return $this->belongsToMany(Teacher::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
