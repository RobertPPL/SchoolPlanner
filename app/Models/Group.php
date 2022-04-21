<?php

namespace App\Models;

use App\Models\Schedule;
use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;
    use TraitUuid;

    protected $fillable = [
        'name', 'agency'
    ];

    public $incrementing = false;

    public function schedule()
    {
        return $this->belongsToMany(Schedule::class);
    }
}
