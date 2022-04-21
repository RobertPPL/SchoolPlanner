<?php

namespace App\Models;

use App\Models\Subject;
use App\Traits\TraitUuid;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;
    use TraitUuid;

    protected $fillable = [
        'name', 'agency'
    ];

    protected static function boot()
    {
        parent::boot();
        $creationCallback = function ($model) {
            if (empty($model->{$model->getKeyName()}))
            {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        };

        static::creating($creationCallback);

        static::deleting(
            function($model)
            {
                $model->subjects()->detach();
                foreach($model->schedules()->get() as $schedule){
                    $schedule->delete();
                }
            }
        );
    }

    public $incrementing = false;

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
