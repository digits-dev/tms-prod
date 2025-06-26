@extends('crudbooster::admin_template')
@section('content')
  <!-- Your html goes here -->
  <div class='panel panel-default'>
    <div class='panel-heading'>Add Bank Account</div>
    <div class='panel-body'>
      <form method='post' action='{{CRUDBooster::mainpath('add-save')}}'>
        <input type='hidden' name='_token' value="{{ csrf_token() }}">
        <div class='form-group col-md-6'>
        <label class="control-label">Company <span class="required">*</span></label>
            <select name="company_id" id="company_id" class="form-control" required>
                <option value="">Please select company</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->company_code }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>

        <div class='form-group col-md-6'>
        <label class="control-label">Branch <span class="required">*</span></label>
            <select name="branch_id" id="branch_id" class="form-control" required>
                <option value="">Please select branch</option>
            </select>
        </div>

        <div class='form-group col-md-3'>
        <label class="control-label">Bank Account 1 <span class="required">*</span></label>
            <input type="text" class="form-control" name="bank_account1" id="bank_account1" required>
        </div>

        <div class='form-group col-md-3'>
        <label class="control-label">Bank Account #1 <span class="required">*</span></label>
            <input type="number" class="form-control" name="bank_account_number1" id="bank_account_number1" required>
        </div>

        <div class='form-group col-md-3'>
        <label class="control-label">Bank Account 2</label>
            <input type="text" class="form-control" name="bank_account2" id="bank_account2">
        </div>

        <div class='form-group col-md-3'>
        <label class="control-label">Bank Account #2</label>
            <input type="number" class="form-control" name="bank_account_number2" id="bank_account_number2">
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
