<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\JobCategory;
use App\Models\Company;

class JobVacancy extends Model
{
    use HasFactory,HasUuids,SoftDeletes;
    
    protected $table = 'companies';
    protected $keyType = "string";

    public $incrementing = false;

    protected $fillable= [
        'title',
        'description',
        'location',
        'salary',
        'type',
        'categoryId',
        'companyId',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    public function jobCategory(){
        return $this->belongsTo(JobCategory::class,'categoryId','id');
    }

    public function company(){
        return $this->belongsTo(Company::class,'companyId','id');
    }
}
