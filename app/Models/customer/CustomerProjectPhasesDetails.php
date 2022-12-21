<?php

namespace App\Models\customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;
use function PHPUnit\Framework\isNull;
use Auth;


class CustomerProjectPhasesDetails extends Model
{
    use HasFactory;

    protected $table = 'customer_project_phases_details';
    protected $primaryKey = 'Id';
    protected $KeyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'CustomerProjectPhaseId',
        'CustomerId',
        'ProjectPhaseId',
        'ProjectPhaseDetailId',
        'Title',
        'Percentage',
        'Checked',
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
