<?php

namespace App\Models\customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Auth;
use Spatie\Activitylog\LogOptions;
use function PHPUnit\Framework\isNull;

class CustomerBusinessProcessFiles extends Model
{
    use HasFactory;
    protected $table = 'customer_business_process_files';
    protected $primaryKey = 'Id';
    protected $KeyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'CustomerId',
        'File',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (isNull($model->Id)) {
                $model->Id = Str::uuid();
                $model->CreatedById = Auth::id() ?? null;
                $model->UpdatedById = Auth::id() ?? null;
            } else {
                $model->UpdatedById = Auth::id() ?? null;
            }
        });
    }
}
