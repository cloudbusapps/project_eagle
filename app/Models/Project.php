<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;


use function PHPUnit\Framework\isNull;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;


class Project extends Model
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    protected $primaryKey = 'Id';
    protected $KeyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        "Id",
        'Name',
        'KickoffDate',
        'ClosedDate',
        'ProjectManager',
        'CreatedById',
        'UpdatedById',

    ];

    public function createdBy()
    {

        // third param is the column of the first param, second param is the column of the current model
        // below will be translated to select * from users where users.Id = Project.CreatedById
        return $this->belongsTo(User::class, 'CreatedById', 'Id');
    }
    public function tasks()
    {
        return $this->hasManyThrough(Task::class, UserStory::class, 'ProjectId', 'UserStoryId','Id','Id');
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (isNull($model->Id)) {
                $model->Id = Str::uuid();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $FullName = $activity->causer->FirstName . ' ' . $activity->causer->LastName;
        $Name = $activity->subject->Name;
        $activity->description = "{$FullName} {$eventName} project <b>{$Name}</b>";
    }
}
