@extends('crudbooster::admin_template')
@section('content')
  <!-- Your html goes here -->
  <div class='panel panel-default'>
    <div class='panel-heading'>Edit Bank Account</div>
    <div class='panel-body'>
      <form method='post' action='{{CRUDBooster::mainpath('edit-save/'.$row->id)}}'>
        <input type='hidden' name='_token' value="{{ csrf_token() }}">
        <div class='form-group col-md-6'>
        <label class="control-label">Company <span class="required">*</span></label>
            <select name="company_id" id="company_id" class="form-control" required>
                <option value="">Please select company</option>
                @foreach ($companies as $company)
                    <option {{ ($row->company_id == $company->company_code) ? 'selected' : '' }} value="{{ $company->company_code }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>

        <div class='form-group col-md-6'>
        <label class="control-label">Branch <span class="required">*</span></label>
            <select name="branch_id" id="branch_id" class="form-control" required>
                <option value="">Please select branch</option>
                @foreach ($branches as $branch)
                    <option {{ ($row->branch_id == $branch->branch_id) ? 'selected' : '' }} value="{{ $branch->branch_id }}">{{ $branch->branch_name }}</option>
                @endforeach
            </select>
        </div>

        <div class='form-group col-md-3'>
        <label class="control-label">Bank Account 1 <span class="required">*</span></label>
            <input type="text" class="form-control" name="bank_account1" id="bank_account1" required value="{{ $row->bank_account1 }}">
        </div>

        <div class='form-group col-md-3'>
        <label class="control-label">Bank Account #1 <span class="required">*</span></label>
            <input type="number" class="form-control" name="bank_account_number1" id="bank_account_number1" required value="{{ $row->bank_account_number1 }}">
        </div>

        <div class='form-group col-md-3'>
        <label class="control-label">Bank Account 2</label>
            <input type="text" class="form-control" name="bank_account2" id="bank_account2" value="{{ $row->bank_account2 }}">
        </div>

        <div class='form-group col-md-3'>
        <label class="control-label">Bank Account #2</label>
            <input type="number" class="form-control" name="bank_account_number2" id="bank_account_number2" value="{{ $row->bank_account_number2 }}">
        </div>


        <div class='form-group col-md-3'>
        <label class="control-label">Bank Tender</label>
            <table class="table table-bordered">
                <thead>
                    <tr style="background: #0047ab; color: white">
                        <th>Bank Name</th>
                        <th>Bank Account</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($bank_masters as $bank_master)
                <tr>
                    <td>{{ $bank_master->bank_name }}</td>
                    <td>
                        <select name="bank_lines_id[{{ $bank_master->id }}]" class="form-control">

                            @foreach ($bank_accounts as $bank)
                                <option {{ ($bank->id == $bank_master->bank_account_lines_id) ? 'selected' : '' }} value="{{ $bank->id }}">{{ $bank->bank_account }}-{{ $bank->bank_account_number }}</option>
                            @endforeach

                        </select>
                    </td>
                </tr>
                @endforeach

                </tbody>
            </table>
        </div>

        <div class='form-group col-md-3'>
        <label class="control-label">Credit Tender</label>
            <table class="table table-bordered">
                <thead>
                    <tr style="background: #0047ab; color: white">
                        <th>Credit Name</th>
                        <th>Bank Account</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($credit_masters as $credit)
                <tr>
                    <td>{{ $credit->credit_name }}</td>
                    <td>
                        <select name="credit_bank_lines_id[{{ $credit->id }}]" class="form-control">

                            @foreach ($bank_accounts as $bank)
                                <option {{ ($bank->id == $credit->bank_account_lines_id) ? 'selected' : '' }} value="{{ $bank->id }}">{{ $bank->bank_account }}-{{ $bank->bank_account_number }}</option>
                            @endforeach

                        </select>
                    </td>
                </tr>
                @endforeach

                </tbody>
            </table>
        </div>

        <div class='form-group col-md-3'>
        <label class="control-label">Other Tender</label>
            <table class="table table-bordered">
                <thead>
                    <tr style="background: #0047ab; color: white">
                        <th>Tender Name</th>
                        <th>Bank Account</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($other_tenders as $tender)
                <tr>
                    <td>{{ $tender->tender_name }}</td>
                    <td>
                        <select name="other_tender_bank_lines_id[{{ $tender->id }}]" class="form-control">

                            @foreach ($bank_accounts as $bank)
                                <option {{ ($bank->id == $tender->bank_account_lines_id) ? 'selected' : '' }} value="{{ $bank->id }}">{{ $bank->bank_account }}-{{ $bank->bank_account_number }}</option>
                            @endforeach

                        </select>
                    </td>
                </tr>
                @endforeach

                </tbody>
            </table>
        </div>

        <div class='form-group col-md-3'>
        <label class="control-label">Cash Tender</label>
            <table class="table table-bordered">
                <thead>
                    <tr style="background: #0047ab; color: white">
                        <th>Tender Name</th>
                        <th>Bank Account</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($cash_masters as $tender)
                <tr>
                    <td>{{ $tender->cash_name }}</td>
                    <td>
                        <select name="cash_tender_bank_lines_id[{{ $tender->id }}]" class="form-control">

                            @foreach ($bank_accounts as $bank)
                                <option {{ ($bank->id == $tender->bank_account_lines_id) ? 'selected' : '' }} value="{{ $bank->id }}">{{ $bank->bank_account }}-{{ $bank->bank_account_number }}</option>
                            @endforeach

                        </select>
                    </td>
                </tr>
                @endforeach

                </tbody>
            </table>
        </div>

    </div>
    <div class='panel-footer'>
      <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">Back</a>
        <button type="submit" id="btnSubmit" class='btn btn-primary pull-right'>Save</button>
    </div>
    </form>
  </div>
@endsection

@push('bottom')

<script>

$(document).ready( function () {
    $("#company_id").change(function(){
        let sel_company = $(this).val();

        $.ajax({
            url:"{{ route('get.branch') }}",
            type:"POST",
            dataType: "json",
            data: {
                _token: "{{ csrf_token() }}",
                company: sel_company,
                id:
                filter:
                item:
            },
            success:function(data) {

                $("#branch_id").removeAttr("disabled");
                $("#branch_id").empty();
                $("#branch_id").append($("<option></option>").attr("value", "").text("Select a branch"));
                $.each(data, function(key,value) {
                    $("#branch_id").append($("<option></option>").attr("value", value.branch_id).text(value.branch_name));
                });
            }
        });
    });
});
</script>

@endpush

