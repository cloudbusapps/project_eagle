<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;
use Auth;
use function PHPUnit\Framework\isNull;

class ResourceCost extends Model
{
    use HasFactory;

    protected $primaryKey = 'Id';
    protected $KeyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'UserId',
        'Level',
        'BasicSalary',
        'DailyRate',
        'HourlyRate',
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
}
