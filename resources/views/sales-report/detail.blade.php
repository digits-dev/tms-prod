@extends('crudbooster::admin_template')
@section('content')

@include('partials.style')

    @if(g('return_url'))
        <p><a title='Return' href='{{g("return_url")}}' class="noprint"><i class='fa fa-chevron-circle-left'></i>
        &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>
    @else
        <p><a title='Main Module' href='{{CRUDBooster::mainpath()}}' class="noprint"><i class='fa fa-chevron-circle-left'></i>
        &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>
    @endif

    <div class='panel panel-default'>
        <div class='panel-heading'>
        <h3 class="box-title text-center"><b>Order Details</b></h3>
        </div>

        <div class='panel-body' id="order-details">

            <div class="col-md-4">
                <div class="table-responsive">
                    <table class="table table-bordered" id="order-details-1">
                        <tbody>
                            <tr>
                                <td style="width: 30%">
                                    <b>Date/Time:</b>
                                </td>
                                <td colspan="2">
                                    {{ $report_details->sales_trx_date }} | {{ $report_details->sales_trx_time }}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <b>Receipt#:</b>
                                </td>
                                <td>
                                    {{ $report_details->receipt_number }}
                                </td>
                                <td>
                                    {{ $report_details->terminal_number }}
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-4 col-md-offset-4">
                <div class="table-responsive">
                    <table class="table table-bordered" id="order-details-2">
                        <tbody>
                            <tr>
                                <td style="width: 30%">
                                    <b>Customer:</b>
                                </td>
                                <td>
                                    {{ $report_details->customer }}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <b>Store:</b>
                                </td>
                                <td>
                                    {{ $report_details->company_name }} | {{ $report_details->branch_name }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <br>

            <div class="col-md-12">
                <div class="box-header text-center">
                    <h3 class="box-title"><b>Order Items</b></h3>
                </div>

                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table table-bordered noselect" id="order-items">
                            <thead>
                                <tr style="background: #0047ab; color: white">
                                    <th width="10%" class="text-center">{{ trans('label.table.item_code') }}</th>
                                    <th width="40%" class="text-center">{{ trans('label.table.item_description') }}</th>
                                    <th width="10%" class="text-center">{{ trans('label.table.qty') }}</th>
                                    <th width="10%" class="text-center">{{ trans('label.table.amount') }}</th>
                                    <th width="10%" class="text-center">{{ trans('label.table.discount') }}</th>
                                    <th width="10%" class="text-center">{{ trans('label.table.discount_code') }}</th>
                                    <th width="10%" class="text-center">{{ trans('label.table.discount_memo') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order_items as $item)
                                    <tr>
                                        <td class="text-center">{{$item['item_code']}} </td>
                                        <td>{{$item['item_description']}} </td>
                                        <td class="text-center">{{$item['qty']}}</td>
                                        <td class="text-center">{{ number_format($item['srp'],3,".",",") }}</td>
                                        <td class="text-center">{{$item['discount']}}</td>
                                        <td class="text-center">{{$item['discount_code']}}</td>
                                        <td class="text-center">{{$item['discount_name']}}</td>
                                    </tr>
                                @endforeach

                                <tr class="tableInfo">
                                    <td colspan="2" align="right"><strong>{{ trans('label.table.total_quantity') }}</strong></td>
                                    <td align="center" colspan="1">{{$order_qty}}</td>
                                    <td align="center" colspan="1">{{ number_format($order_amount,3,".",",") }}</td>
                                    <td colspan="3"></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table style="width: 50%" class="table table-bordered noselect" id="payment-items">
                            <thead>
                                <tr style="background: #2f343a; color: white">
                                    <th width="10%" class="text-center">{{ trans('label.table.payment_type') }}</th>
                                    <th width="10%" class="text-center">{{ trans('label.table.payment_method') }}</th>
                                    <th width="10%" class="text-center">{{ trans('label.table.payee') }}</th>
                                    <th width="10%" class="text-center">{{ trans('label.table.amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order_payments as $item)
                                    <tr>
                                        <td class="text-center">{{$item['type']}} </td>
                                        <td class="text-center">{{(!is_null($item['credit_name'])) ? $item['credit_name'] : $item['payment_method'] }}</td>
                                        <td class="text-center">{{$item['payee']}}</td>
                                        <td class="text-center">{{ number_format($item['amount'],3,".",",") }}</td>
                                    </tr>
                                @endforeach

                                {{-- <tr class="tableInfo">
                                    <td colspan="2" align="right"><strong>{{ trans('label.table.total_quantity') }}</strong></td>
                                    <td align="center" colspan="1">{{$order_qty}}</td>
                                    <td align="center" colspan="1">{{ number_format($order_amount,3,".",",") }}</td>
                                    <td colspan="2"></td>
                                </tr> --}}

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            </div>

        <div class='panel-footer'>
            @if(g('return_url'))
            <a href="{{ g("return_url") }}" class="btn btn-default">{{ trans('label.form.back') }}</a>
            @else
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('label.form.back') }}</a>
            @endif
        </div>
    </div>

@endsection
@push('bottom')
<script type="text/javascript">
$(document).ready(function() {

    $(function(){
        $('body').addClass("sidebar-collapse");
    });

});
</script>
@endpush
