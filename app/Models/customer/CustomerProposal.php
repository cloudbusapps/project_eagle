<?php

namespace App\Models\customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;
use function PHPUnit\Framework\isNull;
use Auth;
use DateTime;


class CustomerProposal extends Model
{
    use HasFactory;

    protected $table = 'customer_proposals';
    protected $primaryKey = 'Id';
    protected $KeyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'CustomerId',
        'DateSubmitted',
        'SignedDateSubmitted',
    ];

    public function getAging()
    {
        $now = time();
        $dateSubmitted = strtotime($this->DateSubmitted);
        $datediff = $now - $dateSubmitted;

        if(isset($this->SignedDateSubmitted)){
            $datediff = strtotime($this->SignedDateSubmitted) - $dateSubmitted;
        }


        return round($datediff / (60 * 60 * 24));
    }

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
