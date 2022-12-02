<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Auth;

class ModuleApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'ModuleId',
        'DesignationId',
        'Level',
        'ApproverId',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function($model) {
            $model->CreatedById = Auth::id() ?? null;
            $model->UpdatedById = Auth::id() ?? null;
        });
    } 
}
