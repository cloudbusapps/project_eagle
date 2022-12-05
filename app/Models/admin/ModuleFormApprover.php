<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\isNull;
use Illuminate\Support\Str;
use Auth;

class ModuleFormApprover extends Model
{
    use HasFactory;

    protected $fillable = [
        'ModuleId',
        'TableId',
        'Level',
        'ApproverId',
        'Date',
        'Status',
        'Remarks',
        'CreatedById',
        'UpdatedById',
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
}