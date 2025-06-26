@extends('crudbooster::admin_template')

@push('head')

<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="Stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

<style type="text/css">

    table.table.table-bordered tr:hover{
        background: #b6b6b6;
    }

    table.table.table-bordered td {
      border: 1px solid black;
    }

    table.table.table-bordered tr {
      border: 1px solid black;
    }

    table.table.table-bordered th {
      border: 1px solid black;
    }
</style>
@endpush

@section('content')

<br>

<div class="box">
    <br>
    <a href="javascript:showSalesItemExport()" id="export-sales-item-report" class="btn btn-primary btn-sm">
        <i class="fa fa-download"></i> Export Sales Item Report
    </a>

    <a href="javascript:showSalesSummaryExport()" id="export-sales-summary-report" class="btn btn-primary btn-sm">
        <i class="fa fa-download"></i> Export Sales Summary Report
    </a>

    <a href="javascript:showSettlementExport()" id="export-settlement-report" class="btn btn-primary btn-sm">
        <i class="fa fa-download"></i> Export Settlement Report
    </a>
    <br>
    <br>
<!-- Your custom  HTML goes here -->
<table class='table table-striped table-bordered' id='sales-report-table'>
  <thead>
      <tr>
        <th>Trx Date</th>
        <th>Trx Time</th>
        <th>Terminal #</th>
        <th>Company</th>
        <th>Branch</th>
        <th>Receipt #</th>
        <th>Source</th>
        <th>Memo</th>
        <th>Action</th>
       </tr>
  </thead>
  <tbody>
    @foreach($result as $row)
      <tr>
        <td>{{$row->sales_trx_date}}</td>
        <td>{{$row->sales_trx_time}}</td>
        <td>{{$row->terminal_number}}</td>
        <td>{{$row->company_name}}</td>
        <td>{{$row->branch_name}}</td>
        <td>{{$row->receipt_number}}</td>
        <td>{{$row->source_name}}</td>
        <td>{{$row->sale_memo}}</td>

        <td>
          @if(CRUDBooster::isRead())
          <a class='btn-detail' title="Detail" href='{{CRUDBooster::mainpath("detail/$row->id")}}'><i class='fa fa-eye'></i></a>
          @endif
        </td>
       </tr>
    @endforeach
  </tbody>
</table>

</div>

<div class='modal fade' tabindex='-1' role='dialog' id='modal-sales-item-export'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button class='close' aria-label='Close' type='button' data-dismiss='modal'>
                    <span aria-hidden='true'>×</span></button>
                <h4 class='modal-title'><i class='fa fa-download'></i> Export Sales Item Report</h4>
            </div>

            <form method='post' target='_blank' action="{{ CRUDBooster::mainpath("export") }}">
            <input type='hidden' name='_token' value="{{ csrf_token() }}">
            {{ CRUDBooster::getUrlParameters() }}
            @if(!empty($filters))
                @foreach ($filters as $keyfilter => $valuefilter )
                    <input type="hidden" name="{{ $keyfilter }}" value="{{ $valuefilter }}">
                @endforeach

            @endif
            <div class='modal-body'>
                <div class='form-group'>
                    <label>File Name</label>
                    <input type='text' name='filename' class='form-control' required value='Export Sales Item {{ CRUDBooster::getCurrentModule()->name }} - {{ date('Y-m-d H:i:s') }}'/>
                </div>
            </div>
            <div class='modal-footer' align='right'>
                <button class='btn btn-default' type='button' data-dismiss='modal'>Close</button>
                <button class='btn btn-primary btn-submit' type='submit'>Submit</button>
            </div>
        </form>
        </div>
    </div>
</div>

<div class='modal fade' tabindex='-1' role='dialog' id='modal-settlement-export'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button class='close' aria-label='Close' type='button' data-dismiss='modal'>
                    <span aria-hidden='true'>×</span></button>
                <h4 class='modal-title'><i class='fa fa-download'></i> Export Settlement Report</h4>
            </div>

            <form method='post' target='_blank' action="{{ CRUDBooster::mainpath("tender-export") }}">
            <input type='hidden' name='_token' value="{{ csrf_token() }}">
            {{ CRUDBooster::getUrlParameters() }}
            @if(!empty($filters))
                @foreach ($filters as $keyfilter => $valuefilter )
                    <input type="hidden" name="{{ $keyfilter }}" value="{{ $valuefilter }}">
                @endforeach

            @endif
            <div class='modal-body'>
                <div class='form-group'>
                    <label>File Name</label>
                    <input type='text' name='filename' class='form-control' required value='Export Settlement {{ CRUDBooster::getCurrentModule()->name }} - {{ date('Y-m-d H:i:s') }}'/>
                </div>
            </div>
            <div class='modal-footer' align='right'>
                <button class='btn btn-default' type='button' data-dismiss='modal'>Close</button>
                <button class='btn btn-primary btn-submit' type='submit'>Submit</button>
            </div>
        </form>
        </div>
    </div>
</div>

<div class='modal fade' tabindex='-1' role='dialog' id='modal-sales-summary-export'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button class='close' aria-label='Close' type='button' data-dismiss='modal'>
                    <span aria-hidden='true'>×</span></button>
                <h4 class='modal-title'><i class='fa fa-download'></i> Export Sales Summary Report</h4>
            </div>

            <form method='post' target='_blank' action="{{ CRUDBooster::mainpath("summary-export") }}">
            <input type='hidden' name='_token' value="{{ csrf_token() }}">
            {{ CRUDBooster::getUrlParameters() }}
            @if(!empty($filters))
                @foreach ($filters as $keyfilter => $valuefilter )
                    <input type="hidden" name="{{ $keyfilter }}" value="{{ $valuefilter }}">
                @endforeach

            @endif
            <div class='modal-body'>
                <div class='form-group'>
                    <label>File Name</label>
                    <input type='text' name='filename' class='form-control' required value='Export Summary {{ CRUDBooster::getCurrentModule()->name }} - {{ date('Y-m-d H:i:s') }}'/>
                </div>
            </div>
            <div class='modal-footer' align='right'>
                <button class='btn btn-default' type='button' data-dismiss='modal'>Close</button>
                <button class='btn btn-primary btn-submit' type='submit'>Submit</button>
            </div>
        </form>
        </div>
    </div>
</div>

@endsection

@push('bottom')
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>

$(document).ready( function () {

    var table = $('#sales-report-table').DataTable();

});

function showSalesItemExport() {
    $('#modal-sales-item-export').modal('show');
}

function showSalesSummaryExport() {
    $('#modal-sales-summary-export').modal('show');
}

function showSettlementExport() {
    $('#modal-settlement-export').modal('show');
}
</script>
@endpush
