<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReport extends Model
{
    use HasFactory;

    protected $table = 'sales_reports';

    protected $fillable = [
        'sales_trx_date',
        'sales_trx_time',
        'terminal_number',
        'companies_id',
        'company',
        'receipt_number',
        'sale_memo',
        'source',
        'customer',
        'cashier',
        'serviced_by',
        'service_charge',
        'evat',
        'gross_amount',
        'total_discount',
        'senior_discount',
        'pwd_discount',
        'dip_discount',
        'inc_tax',
        'exc_tax',
        'sales_tax',
        'tax_rate',
        'tax_sale',
        'total_cost',
        'total_credit',
        'total_tender',
        'change',
        'rendered_cash',
        'cash',
        'branch',
        'record_number',
        'trx_number',
        'bank_account1',
        'bank_account_number1',
        'tender_type1',
        'amount1',
        'tender_rate1',
        'with_held_rate1',
        'bank_charge1',
        'with_held_tax1',
        'net_credit1',
        'tender_memo1',
        'bank_account2',
        'bank_account_number2',
        'tender_type2',
        'amount2',
        'tender_rate2',
        'with_held_rate2',
        'bank_charge2',
        'with_held_tax2',
        'net_credit2',
        'tender_memo2',
        'bank_account3',
        'bank_account_number3',
        'tender_type3',
        'amount3',
        'tender_rate3',
        'with_held_rate3',
        'bank_charge3',
        'with_held_tax3',
        'net_credit3',
        'tender_memo3'
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
