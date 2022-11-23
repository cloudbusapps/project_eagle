<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;


use function PHPUnit\Framework\isNull;

class Resource extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'resources';
    protected $primaryKey = 'Id';
    protected $KeyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        "Id",
        'ProjectId',
        'UserId',

    ];


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
