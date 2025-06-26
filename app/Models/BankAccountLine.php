<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccountLine extends Model
{
    use HasFactory;

    protected $table = 'bank_account_lines';

    protected $fillable = [
        'bank_accounts_id',
        'bank_account',
        'bank_account_number    ',
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
