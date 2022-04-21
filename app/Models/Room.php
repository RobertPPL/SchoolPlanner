<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;
    use TraitUuid;

    protected $fillable = [
        'name', 'agency'
    ];

    public $incrementing = false;

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
                foreach($model->schedules()->get() as $schedule){
                    $schedule->update(['room_id' => null]);
                }
            }
        );
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
