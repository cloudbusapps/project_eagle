<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;


use function PHPUnit\Framework\isNull;
class Task extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tasks';
    protected $primaryKey = 'Id';
    protected $KeyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        "Id",
        'Title',
        'Description',
        'Manhour',
        'ThirdParty',
        'Module',
        'Solution',
        'Assumption',
        'UserStoryId',
        'ProjectId',
        'StartDate',
        'EndDate',
        'UserId',
        'Status',
        'CreatedById',
        'UpdatedById',

    ];

    public function users(){
        return $this->hasOne(User::class);
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
