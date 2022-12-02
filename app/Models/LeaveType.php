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

class LeaveType extends Model
{
    use HasFactory, LogsActivity;

    protected $primaryKey = 'Id';
    protected $KeyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'Id',
        'Name',
        'Status',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function($model) {
            if(isNull($model->Id)) {
                $model->Id = Str::uuid();
                $model->Created_By_Id = Auth::id() ?? null;
                $model->Updated_By_Id = Auth::id() ?? null;
            } else {
                $model->Updated_By_Id = Auth::id() ?? null;
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
            $Name = $activity->subject->Name;
            $FullName = $activity->causer->FirstName . ' ' . $activity->causer->LastName;
            $activity->description = "{$FullName} {$eventName} leave type <b>{$Name}</b>";
        }
    }
}
