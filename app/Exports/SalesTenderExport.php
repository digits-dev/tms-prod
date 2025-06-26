<?php

namespace App\Exports;

use App\Models\SalesReport;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use CRUDBooster;

class SalesTenderExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function headings():array{
        return [
            'DATE',
            'TIME',
            'TM#',
            'COMPANY',
            'BRANCH',
            'RECEIPT NO',
            'SOURCE',
            'CUSTOMER',
            'CASHIER',
            'SERVICED BY',
            'TENDER PAID',
            'CHANGE',
            'GROSS SALES',
            'SENIOR / PWD / DISCOUNT',
            'GROSS SALES LESS DISCOUNT',
            'SERVICE CHARGE',
            'EVAT',
            'TAX',
            'NET SALES',
            'BANK NAME 1',
            'BANK ACCOUNT NUMBER 1',
            'TENDER TYPE 1',
            'TENDER 1 AMOUNT',
            'TENDER 1 CREDIT RATE',
            'TENDER 1 BANK CHARGE',
            'TENDER 1 WITHHELD RATE',
            'TENDER 1 WITHHELD TAX',
            'TENDER 1 NET CREDIT',
            'TENDER NAME 1 (TENDER MEMO)',
            'BANK NAME 2',
            'BANK ACCOUNT NUMBER 2',
            'TENDER TYPE 2',
            'TENDER 2 AMOUNT',
            'TENDER 2 CREDIT RATE',
            'TENDER 2 BANK CHARGE',
            'TENDER 2 WITHHELD RATE',
            'TENDER 2 WITHHELD TAX',
            'TENDER 2 NET CREDIT',
            'TENDER NAME 2 (TENDER MEMO)',
            'BANK NAME 3',
            'BANK ACCOUNT NUMBER 3',
            'TENDER TYPE 3',
            'TENDER 3 AMOUNT',
            'TENDER 3 CREDIT RATE',
            'TENDER 3 BANK CHARGE',
            'TENDER 3 WITHHELD RATE',
            'TENDER 3 WITHHELD TAX',
            'TENDER 3 NET CREDIT',
            'TENDER NAME 3 (TENDER MEMO)'
        ];
    }

    public function map($sales): array {
        $spd_discount = $sales->senior_discount+$sales->pwd_discount+$sales->dip_discount;

        return [
            $sales->sales_trx_date,
            $sales->sales_trx_time,
            $sales->terminal_number,
            $sales->company_name,
            $sales->branch_name,
            $sales->receipt_number,
            $sales->source_name,
            $sales->customer,
            $sales->cashier_name,
            $sales->serviced_by,
            $sales->total_tender,
            $sales->change,
            $sales->gross_amount,
            $spd_discount,
            $sales->gross_amount - $spd_discount - $sales->total_discount,
            $sales->service_charge,
            $sales->evat,
            $sales->sales_tax,
            '',//net sales
            $sales->bank_account1,
            $sales->bank_account_number1,
            $sales->tender_type1,
            $sales->amount1,
            $sales->tender_rate1,
            $sales->with_held_rate1,
            $sales->bank_charge1,
            $sales->with_held_tax1,
            $sales->net_credit1,
            $sales->tender_memo1,
            $sales->bank_account2,
            $sales->bank_account_number2,
            $sales->tender_type2,
            $sales->amount2,
            $sales->tender_rate2,
            $sales->with_held_rate2,
            $sales->bank_charge2,
            $sales->with_held_tax2,
            $sales->net_credit2,
            $sales->tender_memo2,
            $sales->bank_account3,
            $sales->bank_account_number3,
            $sales->tender_type3,
            $sales->amount3,
            $sales->tender_rate3,
            $sales->with_held_rate3,
            $sales->bank_charge3,
            $sales->with_held_tax3,
            $sales->net_credit3,
            $sales->tender_memo3
        ];
    }

    public function query()
    {
        $sales = SalesReport::query()
            ->leftJoin('sales_report_payments','sales_reports.id','=','sales_report_payments.sales_reports_id')
            ->leftJoin('companies','sales_reports.company','=','companies.company_code')
            ->leftJoin('source_types','sales_reports.source','=','source_types.code')
            ->leftJoin('pos_branches', function($joinBranch){
                $joinBranch->on('sales_reports.company', '=', 'pos_branches.company_id');
                $joinBranch->on('sales_reports.branch','=','pos_branches.branch_id');
            })
            ->leftJoin('pos_accounts', function($joinAccount){
                $joinAccount->on('sales_reports.company', '=', 'pos_accounts.company_code');
                $joinAccount->on('sales_reports.cashier','=','pos_accounts.account_code');
            })
            ->leftJoin('credit_masters', function($joinCredit){
                $joinCredit->on('sales_reports.company', '=', 'credit_masters.company_code');
                $joinCredit->on('sales_report_payments.payment_method','=','credit_masters.credit_id');
            })
            ->where('sales_report_payments.amount','!=','0.0000')
            ->select('sales_reports.sales_trx_date',
                'sales_reports.sales_trx_time',
                'sales_reports.terminal_number',
                'companies.company_name',
                'sales_reports.receipt_number',
                'sales_reports.sale_memo',
                'source_types.source_name',
                'sales_reports.customer',
                'pos_accounts.account_name as cashier_name',
                'sales_reports.serviced_by',
                'sales_reports.service_charge',
                'sales_reports.evat',
                'sales_reports.gross_amount',
                'sales_reports.total_discount',
                'sales_reports.senior_discount',
                'sales_reports.pwd_discount',
                'sales_reports.dip_discount',
                'sales_reports.inc_tax',
                'sales_reports.exc_tax',
                'sales_reports.sales_tax',
                'sales_reports.tax_rate',
                'sales_reports.tax_sale',
                'sales_reports.total_cost',
                'sales_reports.total_credit',
                'sales_reports.total_tender',
                'sales_reports.change',
                'sales_reports.rendered_cash',
                'sales_reports.cash',
                'pos_branches.branch_name',
                'sales_reports.record_number',
                'sales_reports.trx_number',
                'sales_report_payments.type',
                'sales_report_payments.payment_method',
                'credit_masters.credit_name',
                'sales_report_payments.payee',
                'sales_report_payments.amount',
                'sales_reports.bank_account1',
                'sales_reports.bank_account_number1',
                'sales_reports.amount1',
                'sales_reports.tender_rate1',
                'sales_reports.with_held_rate1',
                'sales_reports.bank_charge1',
                'sales_reports.with_held_tax1',
                'sales_reports.net_credit1',
                'sales_reports.bank_account2',
                'sales_reports.bank_account_number2',
                'sales_reports.amount2',
                'sales_reports.tender_rate2',
                'sales_reports.with_held_rate2',
                'sales_reports.bank_charge2',
                'sales_reports.with_held_tax2',
                'sales_reports.net_credit2',
                'sales_reports.bank_account3',
                'sales_reports.bank_account_number3',
                'sales_reports.amount3',
                'sales_reports.tender_rate3',
                'sales_reports.with_held_rate3',
                'sales_reports.bank_charge3',
                'sales_reports.with_held_tax3',
                'sales_reports.net_credit3',
                'sales_reports.tender_type1',
                'sales_reports.tender_memo1',
                'sales_reports.tender_type2',
                'sales_reports.tender_memo2',
                'sales_reports.tender_type3',
                'sales_reports.tender_memo3',
            );

        if (request()->has('filter_column')) {
            $filter_column = request()->filter_column;

            $sales->where(function($w) use ($filter_column) {
                foreach($filter_column as $key=>$fc) {

                    $value = @$fc['value'];
                    $type  = @$fc['type'];

                    if($type == 'empty') {
                        $w->whereNull($key)->orWhere($key,'');
                        continue;
                    }

                    if($value=='' || $type=='') continue;

                    if($type == 'between') continue;

                    switch($type) {
                        default:
                            if($key && $type && $value) $w->where($key,$type,$value);
                        break;
                        case 'like':
                        case 'not like':
                            $value = '%'.$value.'%';
                            if($key && $type && $value) $w->where($key,$type,$value);
                        break;
                        case 'in':
                        case 'not in':
                            if($value) {
                                if($key && $value) $w->whereIn($key,$value);
                            }
                        break;
                    }
                }
            });

            foreach($filter_column as $key=>$fc) {
                $value = @$fc['value'];
                $type  = @$fc['type'];
                $sorting = @$fc['sorting'];

                if($sorting!='') {
                    if($key) {
                        $sales->orderby($key,$sorting);
                        $filter_is_orderby = true;
                    }
                }

                if ($type=='between') {
                    if($key && $value) $sales->whereBetween($key,$value);
                }

                else {
                    continue;
                }
            }
        }
        return $sales;
    }
}
