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

class UserSkill extends Model
{
    use HasFactory,LogsActivity;

    protected $table = 'user_skills';
    protected $primaryKey = 'Id';
    protected $KeyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'UserId',
        'Title',
        'CreatedById',
        'UpdatedById'
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
            $point = $activity->subject->Gender == 'Female' ? 'her' : 'his';
            $causerFullName = $activity->causer->FirstName . ' ' . $activity->causer->LastName;
            


            if($activity->causer->IsAdmin==1){
                $user = User::find($activity->subject->UserId);
                $userFullName = $user->FirstName.' '.$user->LastName;
                $activity->description = "{$causerFullName} {$eventName} {$userFullName}'s skills";
            } else{
                $activity->description = "{$causerFullName} {$eventName} {$point} skills</b>";
            }
        }
    }
    
}
