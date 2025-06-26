<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReportLine extends Model
{
    use HasFactory;

    protected $table = 'sales_report_lines';

    protected $fillable = [
        'sales_reports_id',
        'sequence_number',
        'item_code',
        'item_description',
        'qty',
        'srp',
        'total_line_value',
        'discount',
        'discount_code',
        'discount_name',
        'tax'
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
