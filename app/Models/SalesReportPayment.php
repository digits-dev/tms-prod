<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReportPayment extends Model
{
    use HasFactory;

    protected $table = 'sales_report_payments';

    protected $fillable = [
        'sales_reports_id',
        'sequence_number',
        'type',
        'payment_method',
        'payee',
        'amount',
        'trx_date',
        'memo'
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
