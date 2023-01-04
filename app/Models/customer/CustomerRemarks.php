<?php

namespace App\Models\customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;
use function PHPUnit\Framework\isNull;
use Auth;
use Illuminate\Support\Arr;

class CustomerRemarks extends Model
{
    use HasFactory;
    protected $table = 'customer_remarks';
    protected $primaryKey = 'Id';
    protected $KeyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'CustomerId',
        'Remark',
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
