<?php

namespace App\Models;

use App\Models\Schedule;
use App\Traits\TraitUuid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;
    use TraitUuid;

    protected $fillable = [
        'name', 'agency'
    ];

    public $incrementing = false;

    public static function booted()
    {
        static::addGlobalScope('currentAgency', function (Builder $builder) {
            $builder->where('agency', '=', Auth::user()->agency);
        });
    }

    public function schedule()
    {
        return $this->belongsToMany(Schedule::class);
    }
}
