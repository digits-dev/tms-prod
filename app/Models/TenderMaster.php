<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenderMaster extends Model
{
    use HasFactory;

    protected $table = 'tender_masters';

    protected $fillable = [
        'company_code',
        'branch_id',
        'tender_type',
        'bank_masters_id',
        'credit_masters_id',
        'status',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function($model) {
            $model->created_at = date('Y-m-d H:i:s');
        });

        static::updating(function($model) {
            $model->updated_at = date('Y-m-d H:i:s');
        });
   }
}
