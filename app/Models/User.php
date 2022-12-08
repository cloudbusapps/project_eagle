<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Auth;

use function PHPUnit\Framework\isNull;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    protected $primaryKey = 'Id';
    protected $KeyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'About',
        'EmployeeNumber',
        'FirstName',
        'MiddleName',
        'LastName',
        'Gender',
        'Address',
        'ContactNumber',
        'DepartmentId',
        'Title',
        'Profile',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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

    public function getFullNameAttribute() {
        return "{$this->FirstName} {$this->LastName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $point = $activity->subject->Gender == 'Female' ? 'her' : 'his';
        if (!empty($activity->causer)) {
            $FullName = $activity->causer->FirstName . ' ' . $activity->causer->LastName;
            $activity->description = "{$FullName} {$eventName} {$point} personal information";
        } else {
            $FullName = $activity->subject->FirstName . ' ' . $activity->subject->LastName;
            $activity->description = "{$FullName} created an account";
        }
    }


    public function projects()
    {
        return $this->hasMany(Project::class, 'CreatedById');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class, 'CreatedById');
    }

    public function projectsUpdate()
    {
        return $this->belongsTo(Project::class, 'UpdatedById', 'Id');
    }
}
