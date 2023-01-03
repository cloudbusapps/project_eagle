<?php

namespace App\Models\customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;
use function PHPUnit\Framework\isNull;
use Auth;
use Illuminate\Support\Arr;


class CustomerProposalFiles extends Model
{
    use HasFactory;
    protected $table = 'customer_proposal_files';
    protected $primaryKey = 'Id';
    protected $KeyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'CustomerId',
        'File',
        'DateSubmitted',
    ];

    public function getIconAttribute() 
    {
        // return dd($this->File->extension());
        $fileName =explode('.',$this->File);
        $fileExtension = end($fileName);
        $extensions = [
            'jpg' => 'jpg.png',
            'png' => 'png.png',
            'pdf' => 'pdf.png',
            'doc' => 'word.png',
            'xlsx' => 'excel.png',
            'docx' => 'word.png',
        ];

        return Arr::get($extensions,$fileExtension,'unknown.png');
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
