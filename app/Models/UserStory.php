<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use function PHPUnit\Framework\isNull;

class UserStory extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user_story';
    protected $primaryKey = 'Id';
    protected $KeyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'Id',
        'Title',
        'Description',
        'CreatedById',
        'UpdatedById',
        'StartDate',
        'EndDate',
        'ActualStartDate',
        'ActualEndDate',
        'UserId',
        'ProjectId',
        'Admin_Id',
        'Status',
        'PercentComplete',

    ];

    public function computePercent($Id){
        
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
}
