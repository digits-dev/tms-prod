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
    <a href="javascript:showFilter()" id="search-filter" class="btn btn-info btn-sm">
        <i class="fa fa-search"></i> Search Filter
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

<div class='modal fade' tabindex='-1' role='dialog' id='modal-sales-export'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button class='close' aria-label='Close' type='button' data-dismiss='modal'>
                    <span aria-hidden='true'>×</span></button>
                <h4 class='modal-title'><i class='fa fa-download'></i> Export Sales</h4>
            </div>

            <form method='post' target='_blank' action="{{ CRUDBooster::mainpath("export") }}" autocomplete="off">
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
                    <input type='text' name='filename' class='form-control' required value='Export {{ CRUDBooster::getCurrentModule()->name }} - {{ date('Y-m-d H:i:s') }}'/>
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

<div class='modal fade' tabindex='-1' role='dialog' id='modal-tender-export'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button class='close' aria-label='Close' type='button' data-dismiss='modal'>
                    <span aria-hidden='true'>×</span></button>
                <h4 class='modal-title'><i class='fa fa-download'></i> Export Tender</h4>
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
                    <input type='text' name='filename' class='form-control' required value='Export Tender {{ CRUDBooster::getCurrentModule()->name }} - {{ date('Y-m-d H:i:s') }}'/>
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

<div class="modal fade" tabindex="-1" role="dialog" id="modal-filter">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class='close' aria-label='Close' type='button' data-dismiss='modal'>
                    <span aria-hidden='true'>×</span></button>
                <h4 class='modal-title'><i class='fa fa-search'></i> Filter</h4>
            </div>
            <form method='post' target='_blank' action="{{ route('sales.search') }}" autocomplete="off">

            <input type='hidden' name='_token' value="{{ csrf_token() }}">

            <div class="modal-body">
                <div class="row">

                    <div class='col-sm-6'>
                        Date From
                        <div class="form-group">
                            <div class='input-group date' id='datefrom'>
                                <input type='text' name="datefrom" class="form-control" />
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>

                    </div>

                    <div class='col-sm-6'>
                        Date To
                        <div class="form-group">
                            <div class='input-group date' id='dateto'>
                                <input type='text' name="dateto" class="form-control" />
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6">
                        Company
                        <div class="form-group">
                        <select name="company" id="company" class="form-control company" title="Company">
                            <option value="">Please select company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->company_code }}">{{ $company->company_name }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        Branch
                        <div class="form-group">
                        <select name="branch" id="branch" class="form-control branch" title="Branch">
                            <option value="">Please select branch</option>
                        </select>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        Source
                        <div class="form-group">
                        <select name="source" id="source" class="form-control source" title="Source">
                            <option value="">Please select source</option>
                            @foreach ($sources as $source)
                                <option value="{{ $source->code }}">{{ $source->source_name }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        Receipt #
                        <div class="form-group">
                        <input type="text" class="form-control" name="receipt_number" id="receipt_number">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class='btn btn-default' type='button' data-dismiss='modal'>Close</button>
                <button class='btn btn-primary btn-submit' type='submit'>Search</button>

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

    $(function () {
        $('#modal-filter').modal('show');
        $('#datefrom').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });
        $('#dateto').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });
    });

    $("#company").change(function(){
        let sel_company = $(this).val();

        $.ajax({
            url:"{{ route('get.branch') }}",
            type:"POST",
            dataType: "json",
            data: {
                _token: "{{ csrf_token() }}",
                company: sel_company,
            },
            success:function(data) {

                $("#branch").removeAttr("disabled");
                $("#branch").empty();
                $("#branch").append($("<option></option>").attr("value", "").text("Select a branch"));
                $.each(data, function(key,value) {
                    $("#branch").append($("<option></option>").attr("value", value.branch_id).text(value.branch_name));
                });
            }
        });
    });
});

function showSalesExport() {
    $('#modal-sales-export').modal('show');
}

function showTenderExport() {
    $('#modal-tender-export').modal('show');
}

function showFilter(){
    $('#modal-filter').modal('show');
}
</script>
@endpush
