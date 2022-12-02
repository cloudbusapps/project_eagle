<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;
use Auth;
use function PHPUnit\Framework\isNull;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;

class UserEducation extends Model
{
    use HasFactory, LogsActivity;

    protected $primaryKey = 'Id';
    protected $KeyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'UserId',
        'DegreeTitle',
        'School',
        'StartDate',
        'EndDate',
        'Achievement',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function($model) {
            if(isNull($model->Id)) {
                $model->Id = Str::uuid();
                $model->CreatedById = Auth::id() ?? null;
                $model->UpdatedById = Auth::id() ?? null;
            } else {
                $model->UpdatedById = Auth::id() ?? null;
            }
        });
    } 

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        if (!empty($activity->causer)) {
            $DegreeTitle = $activity->subject->DegreeTitle;
            $FullName = $activity->causer->FirstName . ' ' . $activity->causer->LastName;
            $activity->description = "{$FullName} {$eventName} education <b>{$DegreeTitle}</b>";
        }
    }
}
