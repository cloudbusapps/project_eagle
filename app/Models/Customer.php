<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Str;
use function PHPUnit\Framework\isNull;
use Auth;

class Customer extends Model
{
    use HasFactory,Notifiable;

    protected $table = 'customers';
    protected $primaryKey = 'Id';
    protected $KeyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'CustomerName',
        'Industry',
        'Address',
        'ContactPerson',
        'Product',
        'Type',
        'Notes',
        'Link',
        'isComplex',
        'Status',
        'CreatedById',
        'UpdatedById',
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
