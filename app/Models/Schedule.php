<?php

namespace App\Models;

use App\Models\Group;
use App\Traits\TraitUuid;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;
    use TraitUuid;

    protected $fillable = [
        'date', 'teacher_id', 'room_id', 'start_time', 'end_time', 'group_id', 'subject_id', 'agency'
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
            function($model) {
                $model->groups()->detach();
            }
        );
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
}
