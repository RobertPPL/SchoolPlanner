<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    use TraitUuid;

    protected $fillable = [
        'name'
    ];

    public $incrementing = false;

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
