<?php

namespace App\Exports;

use App\Models\SalesReport;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use CRUDBooster;

class SalesReportExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function headings():array{
        return [
            'DATE',
            'TIME',
            'TM#',
            'COMPANY',
            'BRANCH',
            'ITEM CODE',
            'ITEM DESCRIPTION',
            'RECEIPT NO',
            'SOURCE',
            'CUSTOMER',
            'CASHIER',
            'SERVICED BY',
            'DISCOUNT AMOUNT',
            'DISCOUNT CODE',
            'DISCOUNT MEMO',
            'QUANTITY',
            'PRICE PER PRODUCT',
            'TOTAL VALUE'
        ];
    }

    public function map($sales): array {
        return [
            $sales->sales_trx_date,
            $sales->sales_trx_time,
            $sales->terminal_number,
            $sales->company_name,
            $sales->branch_name,
            $sales->item_code,
            $sales->item_description,
            $sales->receipt_number,
            $sales->source_name,
            $sales->customer,
            $sales->cashier_name,
            $sales->serviced_by,
            $sales->discount,
            $sales->discount_code,
            $sales->discount_name,
            $sales->qty,
            $sales->srp,
            $sales->total_line_value
        ];
    }

    public function query()
    {
        $sales = SalesReport::query()
            ->leftJoin('sales_report_lines','sales_reports.id','=','sales_report_lines.sales_reports_id')
            ->leftJoin('companies','sales_reports.company','=','companies.company_code')
            ->leftJoin('source_types','sales_reports.source','=','source_types.code')
            ->leftJoin('items', function($joinItem){
                $joinItem->on('sales_reports.company', '=', 'items.company_code');
                $joinItem->on('sales_report_lines.item_code','=','items.tasteless_code');
            })
            ->leftJoin('pos_accounts', function($joinAccount){
                $joinAccount->on('sales_reports.company', '=', 'pos_accounts.company_code');
                $joinAccount->on('sales_reports.cashier','=','pos_accounts.account_code');
            })
            ->leftJoin('pos_branches', function($joinBranch){
                $joinBranch->on('sales_reports.company', '=', 'pos_branches.company_id');
                $joinBranch->on('sales_reports.branch','=','pos_branches.branch_id');
            })
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
                'sales_report_lines.item_code',
                'items.item_description',
                'sales_report_lines.qty',
                'sales_report_lines.srp',
                'sales_report_lines.total_line_value',
                'sales_report_lines.discount',
                'sales_report_lines.discount_code',
                'sales_report_lines.discount_name');

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
