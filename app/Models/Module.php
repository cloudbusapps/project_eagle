<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Auth;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'ParentId',
        'Title',
        'Icon',
        'Status',
        'SortBy',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function($model) {
            $model->CreatedById = Auth::id() ?? null;
            $model->UpdatedById = Auth::id() ?? null;
        });
    } 
}
