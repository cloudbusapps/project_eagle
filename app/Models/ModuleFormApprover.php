<?php

namespace App\Models;

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
        'Created_By_Id',
        'Updated_By_Id',
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