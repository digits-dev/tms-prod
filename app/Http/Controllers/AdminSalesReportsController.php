<?php namespace App\Http\Controllers;

    use App\Exports\SalesReportExport;
    use App\Exports\SalesSummaryExport;
    use App\Exports\SalesTenderExport;
    use App\Models\SalesReport;
    use App\Models\SalesReportLine;
    use App\Http\Controllers\POSPullController;
    use App\Models\BankAccount;
    use App\Models\BankAccountLine;
    use App\Models\CashMaster;
    use App\Models\Company;
    use App\Models\CreditMaster;
    use App\Models\OtherTenderMaster;
    use App\Models\PosTerminal;
    use App\Models\SalesReportPayment;
    use App\Models\SourceType;
    use Session;
    use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
    use Illuminate\Support\Facades\App;
    use Maatwebsite\Excel\Facades\Excel;

	class AdminSalesReportsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "receipt_number";
			$this->limit = "20";
			$this->orderby = "sales_trx_date,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "sales_reports";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Trx Date","name"=>"sales_trx_date"];
			$this->col[] = ["label"=>"Trx Time","name"=>"sales_trx_time"];
			$this->col[] = ["label"=>"Terminal #","name"=>"terminal_number"];
            $this->col[] = ["label"=>"Company","name"=>"company_code","join"=>"companies,company_name","join_id"=>"company_code"];
            $this->col[] = ["label"=>"Branch","name"=>"branch_id","join"=>"pos_branches,branch_name","join_id"=>"branch_id",
                "join_where"=>"pos_branches.company_id = sales_reports.company_code"];
			$this->col[] = ["label"=>"Receipt #","name"=>"receipt_number"];
            $this->col[] = ["label"=>"Source","name"=>"(select source_name from source_types where code = sales_reports.source) as source"];
			$this->col[] = ["label"=>"Memo","name"=>"sale_memo"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Trx Date','name'=>'sales_trx_date','type'=>'date','validation'=>'required|date','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Trx Time','name'=>'sales_trx_time','type'=>'time','validation'=>'required|date_format:H:i:s','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Terminal #','name'=>'terminal_number','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Company','name'=>'companies_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'companies,company_name'];
			$this->form[] = ['label'=>'Receipt #','name'=>'receipt_number','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Customer','name'=>'customer','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Cashier','name'=>'cashier','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Serviced By','name'=>'serviced_by','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			/*
	        | ----------------------------------------------------------------------
	        | Sub Module
	        | ----------------------------------------------------------------------
			| @label          = Label of action
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        |
	        */
	        $this->sub_module = array();


	        /*
	        | ----------------------------------------------------------------------
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------
	        | @label       = Label of action
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        |
	        */
	        $this->addaction = array();


	        /*
	        | ----------------------------------------------------------------------
	        | Add More Button Selected
	        | ----------------------------------------------------------------------
	        | @label       = Label of action
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button
	        | Then about the action, you should code at actionButtonSelected method
	        |
	        */
	        $this->button_selected = array();


	        /*
	        | ----------------------------------------------------------------------
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------
	        | @message = Text of message
	        | @type    = warning,success,danger,info
	        |
	        */
	        $this->alert = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Add more button to header button
	        | ----------------------------------------------------------------------
	        | @label = Name of button
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        |
	        */
	        $this->index_button = array();
            $this->index_button[] = ['label'=>'Export Sales Report','url'=>"javascript:showSalesExport()",'icon'=>'fa fa-download'];
            $this->index_button[] = ['label'=>'Export Tender Report','url'=>"javascript:showTenderExport()",'icon'=>'fa fa-download'];

	        /*
	        | ----------------------------------------------------------------------
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.
	        |
	        */
	        $this->table_row_color = array();


	        /*
	        | ----------------------------------------------------------------------
	        | You may use this bellow array to add statistic at dashboard
	        | ----------------------------------------------------------------------
	        | @label, @count, @icon, @color
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Add javascript at body
	        | ----------------------------------------------------------------------
	        | javascript code in the variable
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;
            $this->script_js = "
				function showSalesExport() {
					$('#modal-sales-export').modal('show');
				}

                function showTenderExport() {
					$('#modal-tender-export').modal('show');
				}
			";

            /*
	        | ----------------------------------------------------------------------
	        | Include HTML Code before index table
	        | ----------------------------------------------------------------------
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;



	        /*
	        | ----------------------------------------------------------------------
	        | Include HTML Code after index table
	        | ----------------------------------------------------------------------
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
            $this->post_index_html = "
			<div class='modal fade' tabindex='-1' role='dialog' id='modal-sales-export'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button class='close' aria-label='Close' type='button' data-dismiss='modal'>
								<span aria-hidden='true'>×</span></button>
							<h4 class='modal-title'><i class='fa fa-download'></i> Export Sales</h4>
						</div>

						<form method='post' target='_blank' action=".CRUDBooster::mainpath("export").">
                        <input type='hidden' name='_token' value=".csrf_token().">
                        ".CRUDBooster::getUrlParameters()."
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label>File Name</label>
                                <input type='text' name='filename' class='form-control' required value='Export ".CRUDBooster::getCurrentModule()->name ." - ".date('Y-m-d H:i:s')."'/>
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

						<form method='post' target='_blank' action=".CRUDBooster::mainpath("tender-export").">
                        <input type='hidden' name='_token' value=".csrf_token().">
                        ".CRUDBooster::getUrlParameters()."
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label>File Name</label>
                                <input type='text' name='filename' class='form-control' required value='Export Tender ".CRUDBooster::getCurrentModule()->name ." - ".date('Y-m-d H:i:s')."'/>
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
			";


	        /*
	        | ----------------------------------------------------------------------
	        | Include Javascript File
	        | ----------------------------------------------------------------------
	        | URL of your javascript each array
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Add css style at body
	        | ----------------------------------------------------------------------
	        | css code in the variable
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;



	        /*
	        | ----------------------------------------------------------------------
	        | Include css File
	        | ----------------------------------------------------------------------
	        | URL of your css each array
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();


	    }


	    /*
	    | ----------------------------------------------------------------------
	    | Hook for button selected
	    | ----------------------------------------------------------------------
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here

	    }


	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate query of index result
	    | ----------------------------------------------------------------------
	    | @query = current sql query
	    |
	    */
	    public function hook_query_index(&$query) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate row of index table html
	    | ----------------------------------------------------------------------
	    |
	    */
	    public function hook_row_index($column_index,&$column_value) {
	    	//Your code here
	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate data input before add data is execute
	    | ----------------------------------------------------------------------
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after add public static function called
	    | ----------------------------------------------------------------------
	    | @id = last insert id
	    |
	    */
	    public function hook_after_add($id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate data input before update data is execute
	    | ----------------------------------------------------------------------
	    | @postdata = input post data
	    | @id       = current id
	    |
	    */
	    public function hook_before_edit(&$postdata,$id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_after_edit($id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }

        public function getIndex() {
            //First, Add an auth
             if(!CRUDBooster::isView()) CRUDBooster::redirect(CRUDBooster::adminPath(),trans('crudbooster.denied_access'));

             //Create your own query
             $data = [];
             $data['page_title'] = 'Sales Report';
             $data['companies'] = Company::where('status','ACTIVE')->orderBy('company_name','ASC')->get();
             $data['sources'] = SourceType::where('status','ACTIVE')->orderBy('source_name','ASC')->get();
             $data['result'] = SalesReport::orderby('sales_reports.id','desc')
             ->leftJoin('companies','sales_reports.company','=','companies.company_code')
             ->leftJoin('source_types','sales_reports.source','=','source_types.code')
             ->leftJoin('pos_branches', function($join){
                 $join->on('sales_reports.company', '=', 'pos_branches.company_id');
                 $join->on('sales_reports.branch','=','pos_branches.branch_id');
             })
             ->select(
                'sales_reports.id',
                'sales_reports.sales_trx_date',
                'sales_reports.sales_trx_time',
                'sales_reports.terminal_number',
                'companies.company_name',
                'sales_reports.receipt_number',
                'sales_reports.sale_memo',
                'source_types.source_name',
                'sales_reports.customer',
                'sales_reports.cashier',
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
                'sales_reports.trx_number'
             )->orderBy('sales_reports.sales_trx_date','DESC')->limit(100)->get();

             return view('sales-report.index',$data);
        }

        public function getDetail($id)
        {
            //Create an Auth
            if(!CRUDBooster::isRead() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }

            $data = [];
            $data['page_title'] = 'Detail Data';
            $data['report_details'] = SalesReport::where('sales_reports.id',$id)
                ->leftJoin('companies','sales_reports.company','=','companies.company_code')
                ->leftJoin('source_types','sales_reports.source','=','source_types.code')
                ->leftJoin('pos_branches', function($joinBranch){
                    $joinBranch->on('sales_reports.company', '=', 'pos_branches.company_id');
                    $joinBranch->on('sales_reports.branch','=','pos_branches.branch_id');
                })
            ->first();

            $company = $data['report_details']->company;
            $branch = $data['report_details']->branch;

            $data['order_items'] = SalesReportLine::where('sales_report_lines.sales_reports_id',$id)
                ->leftJoin('items', function($joinItems) use ($company){
                    $joinItems->on('sales_report_lines.item_code','=','items.tasteless_code')
                    ->where('items.company_code','=', $company);
                })
                ->select('sales_report_lines.*','items.item_description')
                ->orderBy('sales_report_lines.sequence_number','ASC')
                ->get();

            $data['order_qty'] = SalesReportLine::where('sales_report_lines.sales_reports_id',$id)->sum('qty');
            $data['order_amount'] = SalesReportLine::where('sales_report_lines.sales_reports_id',$id)->sum('total_line_value');


            $data['order_payments'] = SalesReportPayment::where('sales_report_payments.sales_reports_id',$id)
                ->leftJoin('credit_masters', function($joinCredit) use ($company,$branch){
                    $joinCredit->on('sales_report_payments.payment_method','=','credit_masters.credit_id')
                    ->where('credit_masters.company_code', '=', $company)
                    ->where('credit_masters.branch_id', '=', $branch);
                })
                ->select('sales_report_payments.*','credit_masters.credit_name')
                ->get();

            return view('sales-report.detail',$data);
        }

        public function getSalesTransaction($datefrom, $dateto, $terminal)
        {
            $salesDetails = App::call([new POSPullController,'getSalesTransactionByDate'],['datefrom'=>$datefrom,'dateto'=>$dateto,'terminal'=>$terminal]);

            foreach ($salesDetails['data']['record'] as $keyHeader => $valueHeader) {

                $salesRecord = App::call([new POSPullController,'getSalesRecord'],['receipt'=>$valueHeader['fdocument_no'],'terminal'=>$terminal]);

                $headerData = [
                    'sales_trx_date' => $valueHeader['fsale_date'],
                    'sales_trx_time' => $valueHeader['fsale_time'],
                    'terminal_number' => $valueHeader['ftermid'],
                    'receipt_number' => $valueHeader['fdocument_no'],
                    'source' => substr($salesRecord['pos_sale']->finfo, 4, 1),
                    'sale_memo' => (!is_null($salesRecord['pos_sale_info'])) ? $salesRecord['pos_sale_info']->fcode1.' : '.$salesRecord['pos_sale_info']->fstr_data : null,
                    'cashier' => $valueHeader['fcashierid'],
                    'customer' => $salesRecord['pos_sale']->fcustomer_name,
                    'serviced_by' => $salesRecord['pos_sale']->fclerkid,
                    'branch' => $salesRecord['pos_sale']->fofficeid,
                    'company' => $salesRecord['pos_sale']->fcompanyid,
                    'record_number' => $salesRecord['pos_sale']->frecno,
                    'trx_number' => $salesRecord['pos_sale']->ftrx_no,
                    'service_charge' => $salesRecord['pos_sale']->fservice_charge,
                    'evat' => $salesRecord['pos_sale']->fevat,
                    'gross_amount' => $salesRecord['pos_sale']->fgross,
                    'inc_tax' => $salesRecord['pos_sale']->finc_tax,
                    'exc_tax' => $salesRecord['pos_sale']->fexc_tax,
                    'sales_tax' => $salesRecord['pos_sale']->ftax,
                    'tax_rate' => $salesRecord['pos_sale']->ftax_rate,
                    'tax_sale' => $salesRecord['pos_sale']->ftax_sale,
                    'total_cost' => $salesRecord['pos_sale']->ftotal_cost,
                    'total_credit' => $salesRecord['pos_sale']->ftotal_credit,
                    'total_tender' => $salesRecord['pos_sale']->ftotal_tender,
                    'change' => $salesRecord['pos_sale']->fchange,
                    'rendered_cash' => $salesRecord['pos_sale']->frcash,
                    'cash' => $salesRecord['pos_sale']->fcash
                ];

                $salesReportId = SalesReport::firstOrCreate([
                    'sales_trx_date' => $valueHeader['fsale_date'],
                    'terminal_number' => $valueHeader['ftermid'],
                    'receipt_number' => $valueHeader['fdocument_no'],
                    'company' => $salesRecord['pos_sale']->fcompanyid,
                    'branch' => $salesRecord['pos_sale']->fofficeid
                ],$headerData);

                foreach ($valueHeader['product'] as $keyLine => $valueLine) {
                    $lineData = array();
                    if(is_array($valueLine)) {
                        $isDiscounted = (!is_null($salesRecord['pos_sale_discount']) && $valueLine['fseqno'] == $salesRecord['pos_sale_discount']->fseqno) ? true : false;
                        $lineData = [
                            'sales_reports_id' => $salesReportId->id,
                            'item_code' => $valueLine['fproductid'],
                            'qty' => intval($valueLine['fqty']),
                            'srp' => $valueLine['fextprice'],
                            'discount_code' => ($isDiscounted && !is_null($salesRecord['pos_sale_discount_detail'])) ? $salesRecord['pos_sale_discount_detail']->fdiscountid : null,
                            'discount_name' => ($isDiscounted && !is_null($salesRecord['pos_sale_discount_detail'])) ? $salesRecord['pos_sale_discount_detail']->fname.' ('. intval($salesRecord['pos_sale_discount_detail']->fdiscp).'%)': null,
                            'discount' => ($isDiscounted) ? $salesRecord['pos_sale_discount']->famount : null
                        ];

                    }
                    else {
                        unset($lineData);
                        $lineItem = $valueHeader['product'];
                        $isDiscounted = (!is_null($salesRecord['pos_sale_discount']) && $lineItem['fseqno'] == $salesRecord['pos_sale_discount']->fseqno) ? true : false;
                        $lineData = [
                            'sales_reports_id' => $salesReportId->id,
                            'item_code' => $lineItem['fproductid'],
                            'qty' => intval($lineItem['fqty']),
                            'srp' => $lineItem['fextprice'],
                            'discount_code' => ($isDiscounted && !is_null($salesRecord['pos_sale_discount_detail'])) ? $salesRecord['pos_sale_discount_detail']->fdiscountid : null,
                            'discount_name' => ($isDiscounted && !is_null($salesRecord['pos_sale_discount_detail'])) ? $salesRecord['pos_sale_discount_detail']->fname.' ('. intval($salesRecord['pos_sale_discount_detail']->fdiscp).'%)': null,
                            'discount' => (!is_null($salesRecord['pos_sale_discount']) && $lineItem['fseqno'] == $salesRecord['pos_sale_discount']->fseqno) ? $salesRecord['pos_sale_discount']->famount : null
                        ];
                    }
                    if(!is_null($lineData['item_code'])){
                        SalesReportLine::firstOrCreate($lineData);
                    }

                }
                if(isset($valueHeader['payment'])){
                    foreach ($valueHeader['payment'] as $keyLine => $valuePayment) {
                        $paymentData = array();
                        if(is_array($valuePayment)) {
                            $paymentData = [
                                'sales_reports_id' => $salesReportId->id,
                                'sequence_number' => $valuePayment['fseqno'],
                                'type' => $valuePayment['ftype'],
                                'payment_method' => ($valuePayment['ftype'] != "CASH") ? $valuePayment['finfo1'] : null,
                                'payee' => ($valuePayment['ftype'] != "CASH") ? $valuePayment['finfo3'] : null,
                                'amount' => $valuePayment['famount'],
                                'trx_date' => ($valuePayment['ftype'] != "CASH") ? $valuePayment['ftrxdate'] : null
                            ];

                        }
                        else {
                            unset($paymentData);
                            $linePayment = $valueHeader['payment'];
                            $paymentData = [
                                'sales_reports_id' => $salesReportId->id,
                                'type' => $linePayment['ftype'],
                                'sequence_number' => $linePayment['fseqno'],
                                'payment_method' => ($linePayment['ftype'] != "CASH") ? $linePayment['finfo1'] : null,
                                'payee' => ($linePayment['ftype'] != "CASH") ? $linePayment['finfo3'] : null,
                                'amount' => $linePayment['famount'],
                                'trx_date' => ($linePayment['ftype'] != "CASH") ? $linePayment['ftrxdate'] : null

                            ];
                        }
                        if(!is_null($paymentData['type'])){
                            SalesReportPayment::firstOrCreate($paymentData);
                        }

                    }
                }

            }
        }

        public function getDailySalesTransaction()
        {
            $today = date('Y-m-d');
            $date = date_create($today);
            date_sub($date,date_interval_create_from_date_string("1 day"));
            $datefrom = date_format($date,"Ymd");
            $dateto = date_format($date,"Ymd");
            
            // $datefrom = "20240110"; 
            // $dateto = "20240115";

            $terminals = PosTerminal::where('status','ACTIVE')->get();
            // ini_set('max_execution_time', 600);
            foreach ($terminals as $keyTerminal => $valueTerminal) {
                $company = substr($valueTerminal->company_id,-8);

                $salesDetails = App::call([new POSPullController,'getSalesTransactionByTerminalDate'],
                ['datefrom'=>$datefrom,'dateto'=>$dateto,'terminal'=>$valueTerminal->terminal_id,'company'=>$company]);
                \Log::info(json_encode($salesDetails));
                foreach ($salesDetails['data']['record'] as $keyHeader => $valueHeader) {

                    $salesRecord = App::call([new POSPullController,'getSalesRecordByTerminal'],['receipt'=>$valueHeader['fdocument_no'],'company'=>$valueTerminal->company_id,'terminal'=>$valueTerminal->terminal_id]);

                    $headerData = [
                        'sales_trx_date' => $valueHeader['fsale_date'],
                        'sales_trx_time' => $valueHeader['fsale_time'],
                        'terminal_number' => $valueHeader['ftermid'],
                        'receipt_number' => $valueHeader['fdocument_no'],
                        'source' => substr($salesRecord['pos_sale']->finfo, 4, 1),
                        'sale_memo' => (!is_null($salesRecord['pos_sale_info'])) ? $salesRecord['pos_sale_info']->fcode1.' : '.$salesRecord['pos_sale_info']->fstr_data : null,
                        'cashier' => $valueHeader['fcashierid'],
                        'customer' => $salesRecord['pos_sale']->fcustomer_name,
                        'serviced_by' => $salesRecord['pos_sale']->fclerkid,
                        'branch' => $salesRecord['pos_sale']->fofficeid,
                        'company' => $salesRecord['pos_sale']->fcompanyid,
                        'record_number' => $salesRecord['pos_sale']->frecno,
                        'trx_number' => $salesRecord['pos_sale']->ftrx_no,
                        'service_charge' => $salesRecord['pos_sale']->fservice_charge,
                        'evat' => $salesRecord['pos_sale']->fevat,
                        'gross_amount' => $salesRecord['pos_sale']->fgross,
                        'total_discount' => $salesRecord['pos_sale']->ftotal_discount,
                        'senior_discount' => $valueHeader['fscdiscount'],
                        'pwd_discount' => $valueHeader['fpwd_discount'],
                        'dip_discount' => $valueHeader['fdip_discount'],
                        'inc_tax' => $salesRecord['pos_sale']->finc_tax,
                        'exc_tax' => $salesRecord['pos_sale']->fexc_tax,
                        'sales_tax' => $salesRecord['pos_sale']->ftax,
                        'tax_rate' => $salesRecord['pos_sale']->ftax_rate,
                        'tax_sale' => $salesRecord['pos_sale']->ftax_sale,
                        'total_cost' => $salesRecord['pos_sale']->ftotal_cost,
                        'total_credit' => $salesRecord['pos_sale']->ftotal_credit,
                        'total_tender' => $salesRecord['pos_sale']->ftotal_tender,
                        'change' => $salesRecord['pos_sale']->fchange,
                        'rendered_cash' => $salesRecord['pos_sale']->frcash,
                        'cash' => $salesRecord['pos_sale']->fcash
                    ];

                    $salesReportId = SalesReport::firstOrCreate([
                        'company' => $salesRecord['pos_sale']->fcompanyid,
                        'terminal_number' => $valueHeader['ftermid'],
                        'receipt_number' => $valueHeader['fdocument_no']
                    ],$headerData);


                    foreach ($valueHeader['product'] as $keyLine => $valueLine) {
                        $lineData = array();
                        $discountAmount = ($salesRecord['pos_sale_product'][$keyLine]->fdiscount) +
                            ($salesRecord['pos_sale_product'][$keyLine]->fscdiscount) +
                            ($salesRecord['pos_sale_product'][$keyLine]->fdiscount1) +
                            ($salesRecord['pos_sale_product'][$keyLine]->fdiscount2);

                        if(is_array($valueLine)) {
                            $productItem = $valueLine['fproductid'];
                            $isDiscounted = (!is_null(json_encode($salesRecord['pos_sale_product_discount'][(string)$productItem]))) ? true : false;
                            $productItemDiscount = json_decode(json_encode($salesRecord['pos_sale_product_discount'][(string)$productItem]), true);

                            $lineData = [
                                'sales_reports_id' => $salesReportId->id,
                                'sequence_number' => $valueLine['fseqno'],
                                'item_code' => $valueLine['fproductid'],
                                'qty' => intval($valueLine['fqty']),
                                'srp' => $valueLine['fextprice'],
                                'total_line_value' => $valueLine['ftotal_line'],
                                'discount_code' => ($isDiscounted) ? $productItemDiscount['fpriceid'] : null,
                                'discount_name' => ($isDiscounted) ? $productItemDiscount['fname'] : null,
                                'discount' => ($isDiscounted) ? $discountAmount : null
                            ];

                        }
                        else {
                            unset($lineData);
                            $lineItem = $valueHeader['product'];
                            $isDiscounted = (!is_null(json_encode($salesRecord['pos_sale_product_discount'][(string)$lineItem['fproductid']]))) ? true : false;
                            $productItemDiscount = json_decode(json_encode($salesRecord['pos_sale_product_discount'][(string)$lineItem['fproductid']]), true);

                            $lineData = [
                                'sales_reports_id' => $salesReportId->id,
                                'sequence_number' => $lineItem['fseqno'],
                                'item_code' => $lineItem['fproductid'],
                                'qty' => intval($lineItem['fqty']),
                                'srp' => $lineItem['fextprice'],
                                'total_line_value' => $lineItem['ftotal_line'],
                                'discount_code' => ($isDiscounted) ? $productItemDiscount['fpriceid'] : null,
                                'discount_name' => ($isDiscounted) ? $productItemDiscount['fname'] : null,
                                'discount' => ($isDiscounted) ? $discountAmount : null
                            ];
                        }
                        if(!is_null($lineData['item_code'])){
                            try {
                                SalesReportLine::firstOrCreate([
                                    'sales_reports_id' => $salesReportId->id,
                                    'item_code' => $lineData['item_code'],
                                    'sequence_number' => $lineData['sequence_number']
                                ],$lineData);
                            } catch (\Throwable $th) {
                                continue;
                            }

                        }

                    }
                    if(isset($valueHeader['payment']) || isset($valueHeader->payment)){

                        foreach ($valueHeader['payment'] as $keyLine => $valuePayment) {

                            $paymentData = array();
                            $checkPayment = array();
                            if(is_array($valuePayment)) {
                                $checkPayment = [
                                    'sales_reports_id' => $salesReportId->id,
                                    'sequence_number' => $valuePayment['fseqno'],
                                    'type' => $valuePayment['ftype'],
                                    'amount' => $valuePayment['famount'],
                                    'memo' => $valuePayment['fmemo']
                                ];

                                $paymentData = [
                                    'sales_reports_id' => $salesReportId->id,
                                    'sequence_number' => $valuePayment['fseqno'],
                                    'type' => $valuePayment['ftype'],
                                    'payment_method' => (!in_array($valuePayment['ftype'] ,["CASH"])) ? $valuePayment['finfo1'] : null,
                                    'payee' => (!in_array($valuePayment['ftype'] ,["CASH"]) && !empty($valuePayment['finfo3'])) ? $valuePayment['finfo3'] : null,
                                    'amount' => $valuePayment['famount'],
                                    'trx_date' => (!in_array($valuePayment['ftype'] ,["CASH"]) && !empty($valuePayment['ftrxdate'])) ? $valuePayment['ftrxdate'] : null,
                                    'memo' => (!empty($valuePayment['fmemo'])) ? $valuePayment['fmemo'] : null
                                ];

                            }
                            else {
                                unset($paymentData);
                                unset($checkPayment);
                                $linePayment = $valueHeader['payment'];
                                $checkPayment = [
                                    'sales_reports_id' => $salesReportId->id,
                                    'sequence_number' => $linePayment['fseqno'],
                                    'type' => $linePayment['ftype'],
                                    'amount' => $linePayment['famount'],
                                    'memo' => $linePayment['fmemo']
                                ];
                                $paymentData = [
                                    'sales_reports_id' => $salesReportId->id,
                                    'sequence_number' => $linePayment['fseqno'],
                                    'type' => $linePayment['ftype'],
                                    'payment_method' => (!in_array($linePayment['ftype'] ,["CASH"])) ? $linePayment['finfo1'] : null,
                                    'payee' => (!in_array($linePayment['ftype'] ,["CASH"]) && !empty($linePayment['finfo3'])) ? $linePayment['finfo3'] : null,
                                    'amount' => $linePayment['famount'],
                                    'trx_date' => (!in_array($linePayment['ftype'] ,["CASH"]) && !empty($linePayment['ftrxdate'])) ? $linePayment['ftrxdate'] : null,
                                    'memo' => (!empty($linePayment['fmemo'])) ? $linePayment['fmemo'] : null

                                ];
                            }
                            if(!is_null($paymentData['type'])){
                                try {
                                    SalesReportPayment::firstOrCreate($paymentData);

                                    if($paymentData['type'] == "CREDIT"){

                                        $credit = CreditMaster::where('company_code',$salesRecord['pos_sale']->fcompanyid)
                                            ->where('branch_id',$salesRecord['pos_sale']->fofficeid)
                                            ->where('credit_id',$paymentData['payment_method'])
                                            ->leftJoin('bank_account_lines','credit_masters.bank_account_lines_id','=','bank_account_lines.id')
                                            ->first();

                                        $bankCharge = $paymentData['amount'] * $credit->tender_rate;
                                        $withHeldTax = $paymentData['amount'] * $credit->with_held_rate;

                                        switch($paymentData['sequence_number']){
                                            case 1: case "1":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account1' => $credit->bank_account,
                                                    'bank_account_number1' => $credit->bank_account_number,
                                                    'tender_type1' => "CREDIT",
                                                    'amount1' => $paymentData['amount'],
                                                    'tender_rate1' => $credit->tender_rate,
                                                    'with_held_rate1' => $credit->with_held_rate,
                                                    'bank_charge1' => $bankCharge,
                                                    'with_held_tax1' => $withHeldTax,
                                                    'net_credit1' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;

                                            case 2:  case "2":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account2' => $credit->bank_account,
                                                    'bank_account_number2' => $credit->bank_account_number,
                                                    'tender_type2' => "CREDIT",
                                                    'amount2' => $paymentData['amount'],
                                                    'tender_rate2' => $credit->tender_rate,
                                                    'with_held_rate2' => $credit->with_held_rate,
                                                    'bank_charge2' => $bankCharge,
                                                    'with_held_tax2' => $withHeldTax,
                                                    'net_credit2' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;

                                            case 3:  case "3":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account3' => $credit->bank_account,
                                                    'bank_account_number3' => $credit->bank_account_number,
                                                    'tender_type3' => "CREDIT",
                                                    'amount3' => $paymentData['amount'],
                                                    'tender_rate3' => $credit->tender_rate,
                                                    'with_held_rate3' => $credit->with_held_rate,
                                                    'bank_charge3' => $bankCharge,
                                                    'with_held_tax3' => $withHeldTax,
                                                    'net_credit3' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;
                                        }
                                    }

                                    elseif($paymentData['type'] == "OTHERS"){

                                        $otherTender = OtherTenderMaster::where('company_code',$salesRecord['pos_sale']->fcompanyid)
                                            ->where('branch_id',$salesRecord['pos_sale']->fofficeid)
                                            ->where('tender_id',$paymentData['payment_method'])
                                            ->leftJoin('bank_account_lines','other_tender_masters.bank_account_lines_id','=','bank_account_lines.id')
                                            ->first();

                                        $bankCharge = $paymentData['amount'] * $otherTender->tender_rate;
                                        $withHeldTax = $paymentData['amount'] * $otherTender->with_held_rate;

                                        switch($paymentData['sequence_number']){
                                            case 1: case "1":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account1' => $otherTender->bank_account,
                                                    'bank_account_number1' => $otherTender->bank_account_number,
                                                    'tender_type1' => "OTHERS",
                                                    'amount1' => $paymentData['amount'],
                                                    'tender_rate1' => $otherTender->tender_rate,
                                                    'with_held_rate1' => $otherTender->with_held_rate,
                                                    'bank_charge1' => $bankCharge,
                                                    'with_held_tax1' => $withHeldTax,
                                                    'tender_memo1' => $paymentData['payment_method'],
                                                    'net_credit1' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;

                                            case 2:  case "2":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account2' => $otherTender->bank_account,
                                                    'bank_account_number2' => $otherTender->bank_account_number,
                                                    'tender_type2' => "OTHERS",
                                                    'amount2' => $paymentData['amount'],
                                                    'tender_rate2' => $otherTender->tender_rate,
                                                    'with_held_rate2' => $otherTender->with_held_rate,
                                                    'bank_charge2' => $bankCharge,
                                                    'with_held_tax2' => $withHeldTax,
                                                    'tender_memo2' => $paymentData['payment_method'],
                                                    'net_credit2' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;

                                            case 3:  case "3":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account3' => $otherTender->bank_account,
                                                    'bank_account_number3' => $otherTender->bank_account_number,
                                                    'tender_type3' => "OTHERS",
                                                    'amount3' => $paymentData['amount'],
                                                    'tender_rate3' => $otherTender->tender_rate,
                                                    'with_held_rate3' => $otherTender->with_held_rate,
                                                    'bank_charge3' => $bankCharge,
                                                    'with_held_tax3' => $withHeldTax,
                                                    'tender_memo3' => $paymentData['payment_method'],
                                                    'net_credit3' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;
                                        }
                                    }

                                    elseif($paymentData['type'] == "CHARGE"){

                                        $otherTender = OtherTenderMaster::where('company_code',$salesRecord['pos_sale']->fcompanyid)
                                            ->where('branch_id',$salesRecord['pos_sale']->fofficeid)
                                            ->where('tender_id',$paymentData['payment_method'])
                                            ->leftJoin('bank_account_lines','other_tender_masters.bank_account_lines_id','=','bank_account_lines.id')
                                            ->first();

                                        $bankCharge = $paymentData['amount'] * $otherTender->tender_rate;
                                        $withHeldTax = $paymentData['amount'] * $otherTender->with_held_rate;

                                        switch($paymentData['sequence_number']){
                                            case 1: case "1":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account1' => $otherTender->bank_account,
                                                    'bank_account_number1' => $otherTender->bank_account_number,
                                                    'tender_type1' => "CHARGE",
                                                    'amount1' => $paymentData['amount'],
                                                    'tender_rate1' => $otherTender->tender_rate,
                                                    'with_held_rate1' => $otherTender->with_held_rate,
                                                    'bank_charge1' => $bankCharge,
                                                    'with_held_tax1' => $withHeldTax,
                                                    'tender_memo1' => $paymentData['payment_method'],
                                                    'net_credit1' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;

                                            case 2:  case "2":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account2' => $otherTender->bank_account,
                                                    'bank_account_number2' => $otherTender->bank_account_number,
                                                    'tender_type2' => "CHARGE",
                                                    'amount2' => $paymentData['amount'],
                                                    'tender_rate2' => $otherTender->tender_rate,
                                                    'with_held_rate2' => $otherTender->with_held_rate,
                                                    'bank_charge2' => $bankCharge,
                                                    'with_held_tax2' => $withHeldTax,
                                                    'tender_memo2' => $paymentData['payment_method'],
                                                    'net_credit2' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;

                                            case 3:  case "3":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account3' => $otherTender->bank_account,
                                                    'bank_account_number3' => $otherTender->bank_account_number,
                                                    'tender_type3' => "CHARGE",
                                                    'amount3' => $paymentData['amount'],
                                                    'tender_rate3' => $otherTender->tender_rate,
                                                    'with_held_rate3' => $otherTender->with_held_rate,
                                                    'bank_charge3' => $bankCharge,
                                                    'with_held_tax3' => $withHeldTax,
                                                    'tender_memo3' => $paymentData['payment_method'],
                                                    'net_credit3' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;
                                        }
                                    }

                                    elseif($paymentData['type'] == "CASH"){

                                        $cash = CashMaster::where('company_code',$salesRecord['pos_sale']->fcompanyid)
                                            ->where('branch_id',$salesRecord['pos_sale']->fofficeid)
                                            ->leftJoin('bank_account_lines','cash_masters.bank_account_lines_id','=','bank_account_lines.id')
                                            ->first();

                                        $bankCharge = $paymentData['amount'] * $cash->tender_rate;
                                        $withHeldTax = $paymentData['amount'] * $cash->with_held_rate;

                                        switch($paymentData['sequence_number']){
                                            case 1: case "1":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account1' => $cash->bank_account,
                                                    'bank_account_number1' => $cash->bank_account_number,
                                                    'tender_type1' => "CASH",
                                                    'amount1' => $paymentData['amount'],
                                                    'tender_rate1' => $cash->tender_rate,
                                                    'with_held_rate1' => $cash->with_held_rate,
                                                    'bank_charge1' => $bankCharge,
                                                    'with_held_tax1' => $withHeldTax,
                                                    'net_credit1' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;

                                            case 2:  case "2":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account2' => $cash->bank_account,
                                                    'bank_account_number2' => $cash->bank_account_number,
                                                    'tender_type2' => "CASH",
                                                    'amount2' => $paymentData['amount'],
                                                    'tender_rate2' => $cash->tender_rate,
                                                    'with_held_rate2' => $cash->with_held_rate,
                                                    'bank_charge2' => $bankCharge,
                                                    'with_held_tax2' => $withHeldTax,
                                                    'net_credit2' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;

                                            case 3:  case "3":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account3' => $cash->bank_account,
                                                    'bank_account_number3' => $cash->bank_account_number,
                                                    'tender_type3' => "CASH",
                                                    'amount3' => $paymentData['amount'],
                                                    'tender_rate3' => $cash->tender_rate,
                                                    'with_held_rate3' => $cash->with_held_rate,
                                                    'bank_charge3' => $bankCharge,
                                                    'with_held_tax3' => $withHeldTax,
                                                    'net_credit3' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;
                                        }
                                    }

                                } catch (\Throwable $th) {
                                    continue;
                                }

                            }

                        }
                    }

                }

            }
        }
        
        public function getDailySalesTransactionByDate($date_from, $date_to)
        {
            $datefrom = $date_from;
            $dateto = $date_to;

            $terminals = PosTerminal::where('status','ACTIVE')->get();
            // ini_set('max_execution_time', 600);
            foreach ($terminals as $keyTerminal => $valueTerminal) {
                $company = substr($valueTerminal->company_id,-8);

                $salesDetails = App::call([new POSPullController,'getSalesTransactionByTerminalDate'],
                ['datefrom'=>$datefrom,'dateto'=>$dateto,'terminal'=>$valueTerminal->terminal_id,'company'=>$company]);
                \Log::info(json_encode($salesDetails));
                foreach ($salesDetails['data']['record'] as $keyHeader => $valueHeader) {

                    $salesRecord = App::call([new POSPullController,'getSalesRecordByTerminal'],['receipt'=>$valueHeader['fdocument_no'],'company'=>$valueTerminal->company_id,'terminal'=>$valueTerminal->terminal_id]);

                    $headerData = [
                        'sales_trx_date' => $valueHeader['fsale_date'],
                        'sales_trx_time' => $valueHeader['fsale_time'],
                        'terminal_number' => $valueHeader['ftermid'],
                        'receipt_number' => $valueHeader['fdocument_no'],
                        'source' => substr($salesRecord['pos_sale']->finfo, 4, 1),
                        'sale_memo' => (!is_null($salesRecord['pos_sale_info'])) ? $salesRecord['pos_sale_info']->fcode1.' : '.$salesRecord['pos_sale_info']->fstr_data : null,
                        'cashier' => $valueHeader['fcashierid'],
                        'customer' => $salesRecord['pos_sale']->fcustomer_name,
                        'serviced_by' => $salesRecord['pos_sale']->fclerkid,
                        'branch' => $salesRecord['pos_sale']->fofficeid,
                        'company' => $salesRecord['pos_sale']->fcompanyid,
                        'record_number' => $salesRecord['pos_sale']->frecno,
                        'trx_number' => $salesRecord['pos_sale']->ftrx_no,
                        'service_charge' => $salesRecord['pos_sale']->fservice_charge,
                        'evat' => $salesRecord['pos_sale']->fevat,
                        'gross_amount' => $salesRecord['pos_sale']->fgross,
                        'total_discount' => $salesRecord['pos_sale']->ftotal_discount,
                        'senior_discount' => $valueHeader['fscdiscount'],
                        'pwd_discount' => $valueHeader['fpwd_discount'],
                        'dip_discount' => $valueHeader['fdip_discount'],
                        'inc_tax' => $salesRecord['pos_sale']->finc_tax,
                        'exc_tax' => $salesRecord['pos_sale']->fexc_tax,
                        'sales_tax' => $salesRecord['pos_sale']->ftax,
                        'tax_rate' => $salesRecord['pos_sale']->ftax_rate,
                        'tax_sale' => $salesRecord['pos_sale']->ftax_sale,
                        'total_cost' => $salesRecord['pos_sale']->ftotal_cost,
                        'total_credit' => $salesRecord['pos_sale']->ftotal_credit,
                        'total_tender' => $salesRecord['pos_sale']->ftotal_tender,
                        'change' => $salesRecord['pos_sale']->fchange,
                        'rendered_cash' => $salesRecord['pos_sale']->frcash,
                        'cash' => $salesRecord['pos_sale']->fcash
                    ];

                    $salesReportId = SalesReport::firstOrCreate([
                        'company' => $salesRecord['pos_sale']->fcompanyid,
                        'terminal_number' => $valueHeader['ftermid'],
                        'receipt_number' => $valueHeader['fdocument_no']
                    ],$headerData);


                    foreach ($valueHeader['product'] as $keyLine => $valueLine) {
                        $lineData = array();
                        $discountAmount = ($salesRecord['pos_sale_product'][$keyLine]->fdiscount) +
                            ($salesRecord['pos_sale_product'][$keyLine]->fscdiscount) +
                            ($salesRecord['pos_sale_product'][$keyLine]->fdiscount1) +
                            ($salesRecord['pos_sale_product'][$keyLine]->fdiscount2);

                        if(is_array($valueLine)) {
                            $productItem = $valueLine['fproductid'];
                            $isDiscounted = (!is_null(json_encode($salesRecord['pos_sale_product_discount'][(string)$productItem]))) ? true : false;
                            $productItemDiscount = json_decode(json_encode($salesRecord['pos_sale_product_discount'][(string)$productItem]), true);

                            $lineData = [
                                'sales_reports_id' => $salesReportId->id,
                                'sequence_number' => $valueLine['fseqno'],
                                'item_code' => $valueLine['fproductid'],
                                'qty' => intval($valueLine['fqty']),
                                'srp' => $valueLine['fextprice'],
                                'total_line_value' => $valueLine['ftotal_line'],
                                'discount_code' => ($isDiscounted) ? $productItemDiscount['fpriceid'] : null,
                                'discount_name' => ($isDiscounted) ? $productItemDiscount['fname'] : null,
                                'discount' => ($isDiscounted) ? $discountAmount : null
                            ];

                        }
                        else {
                            unset($lineData);
                            $lineItem = $valueHeader['product'];
                            $isDiscounted = (!is_null(json_encode($salesRecord['pos_sale_product_discount'][(string)$lineItem['fproductid']]))) ? true : false;
                            $productItemDiscount = json_decode(json_encode($salesRecord['pos_sale_product_discount'][(string)$lineItem['fproductid']]), true);

                            $lineData = [
                                'sales_reports_id' => $salesReportId->id,
                                'sequence_number' => $lineItem['fseqno'],
                                'item_code' => $lineItem['fproductid'],
                                'qty' => intval($lineItem['fqty']),
                                'srp' => $lineItem['fextprice'],
                                'total_line_value' => $lineItem['ftotal_line'],
                                'discount_code' => ($isDiscounted) ? $productItemDiscount['fpriceid'] : null,
                                'discount_name' => ($isDiscounted) ? $productItemDiscount['fname'] : null,
                                'discount' => ($isDiscounted) ? $discountAmount : null
                            ];
                        }
                        if(!is_null($lineData['item_code'])){
                            try {
                                SalesReportLine::firstOrCreate([
                                    'sales_reports_id' => $salesReportId->id,
                                    'item_code' => $lineData['item_code'],
                                    'sequence_number' => $lineData['sequence_number']
                                ],$lineData);
                            } catch (\Throwable $th) {
                                continue;
                            }

                        }

                    }
                    if(isset($valueHeader['payment']) || isset($valueHeader->payment)){

                        foreach ($valueHeader['payment'] as $keyLine => $valuePayment) {

                            $paymentData = array();
                            $checkPayment = array();
                            if(is_array($valuePayment)) {
                                $checkPayment = [
                                    'sales_reports_id' => $salesReportId->id,
                                    'sequence_number' => $valuePayment['fseqno'],
                                    'type' => $valuePayment['ftype'],
                                    'amount' => $valuePayment['famount'],
                                    'memo' => $valuePayment['fmemo']
                                ];

                                $paymentData = [
                                    'sales_reports_id' => $salesReportId->id,
                                    'sequence_number' => $valuePayment['fseqno'],
                                    'type' => $valuePayment['ftype'],
                                    'payment_method' => (!in_array($valuePayment['ftype'] ,["CASH"])) ? $valuePayment['finfo1'] : null,
                                    'payee' => (!in_array($valuePayment['ftype'] ,["CASH"]) && !empty($valuePayment['finfo3'])) ? $valuePayment['finfo3'] : null,
                                    'amount' => $valuePayment['famount'],
                                    'trx_date' => (!in_array($valuePayment['ftype'] ,["CASH"]) && !empty($valuePayment['ftrxdate'])) ? $valuePayment['ftrxdate'] : null,
                                    'memo' => (!empty($valuePayment['fmemo'])) ? $valuePayment['fmemo'] : null
                                ];

                            }
                            else {
                                unset($paymentData);
                                unset($checkPayment);
                                $linePayment = $valueHeader['payment'];
                                $checkPayment = [
                                    'sales_reports_id' => $salesReportId->id,
                                    'sequence_number' => $linePayment['fseqno'],
                                    'type' => $linePayment['ftype'],
                                    'amount' => $linePayment['famount'],
                                    'memo' => $linePayment['fmemo']
                                ];
                                $paymentData = [
                                    'sales_reports_id' => $salesReportId->id,
                                    'sequence_number' => $linePayment['fseqno'],
                                    'type' => $linePayment['ftype'],
                                    'payment_method' => (!in_array($linePayment['ftype'] ,["CASH"])) ? $linePayment['finfo1'] : null,
                                    'payee' => (!in_array($linePayment['ftype'] ,["CASH"]) && !empty($linePayment['finfo3'])) ? $linePayment['finfo3'] : null,
                                    'amount' => $linePayment['famount'],
                                    'trx_date' => (!in_array($linePayment['ftype'] ,["CASH"]) && !empty($linePayment['ftrxdate'])) ? $linePayment['ftrxdate'] : null,
                                    'memo' => (!empty($linePayment['fmemo'])) ? $linePayment['fmemo'] : null

                                ];
                            }
                            if(!is_null($paymentData['type'])){
                                try {
                                    SalesReportPayment::firstOrCreate($paymentData);

                                    if($paymentData['type'] == "CREDIT"){

                                        $credit = CreditMaster::where('company_code',$salesRecord['pos_sale']->fcompanyid)
                                            ->where('branch_id',$salesRecord['pos_sale']->fofficeid)
                                            ->where('credit_id',$paymentData['payment_method'])
                                            ->leftJoin('bank_account_lines','credit_masters.bank_account_lines_id','=','bank_account_lines.id')
                                            ->first();

                                        $bankCharge = $paymentData['amount'] * $credit->tender_rate;
                                        $withHeldTax = $paymentData['amount'] * $credit->with_held_rate;

                                        switch($paymentData['sequence_number']){
                                            case 1: case "1":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account1' => $credit->bank_account,
                                                    'bank_account_number1' => $credit->bank_account_number,
                                                    'tender_type1' => "CREDIT",
                                                    'amount1' => $paymentData['amount'],
                                                    'tender_rate1' => $credit->tender_rate,
                                                    'with_held_rate1' => $credit->with_held_rate,
                                                    'bank_charge1' => $bankCharge,
                                                    'with_held_tax1' => $withHeldTax,
                                                    'net_credit1' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;

                                            case 2:  case "2":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account2' => $credit->bank_account,
                                                    'bank_account_number2' => $credit->bank_account_number,
                                                    'tender_type2' => "CREDIT",
                                                    'amount2' => $paymentData['amount'],
                                                    'tender_rate2' => $credit->tender_rate,
                                                    'with_held_rate2' => $credit->with_held_rate,
                                                    'bank_charge2' => $bankCharge,
                                                    'with_held_tax2' => $withHeldTax,
                                                    'net_credit2' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;

                                            case 3:  case "3":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account3' => $credit->bank_account,
                                                    'bank_account_number3' => $credit->bank_account_number,
                                                    'tender_type3' => "CREDIT",
                                                    'amount3' => $paymentData['amount'],
                                                    'tender_rate3' => $credit->tender_rate,
                                                    'with_held_rate3' => $credit->with_held_rate,
                                                    'bank_charge3' => $bankCharge,
                                                    'with_held_tax3' => $withHeldTax,
                                                    'net_credit3' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;
                                        }
                                    }

                                    elseif($paymentData['type'] == "OTHERS"){

                                        $otherTender = OtherTenderMaster::where('company_code',$salesRecord['pos_sale']->fcompanyid)
                                            ->where('branch_id',$salesRecord['pos_sale']->fofficeid)
                                            ->where('tender_id',$paymentData['payment_method'])
                                            ->leftJoin('bank_account_lines','other_tender_masters.bank_account_lines_id','=','bank_account_lines.id')
                                            ->first();

                                        $bankCharge = $paymentData['amount'] * $otherTender->tender_rate;
                                        $withHeldTax = $paymentData['amount'] * $otherTender->with_held_rate;

                                        switch($paymentData['sequence_number']){
                                            case 1: case "1":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account1' => $otherTender->bank_account,
                                                    'bank_account_number1' => $otherTender->bank_account_number,
                                                    'tender_type1' => "OTHERS",
                                                    'amount1' => $paymentData['amount'],
                                                    'tender_rate1' => $otherTender->tender_rate,
                                                    'with_held_rate1' => $otherTender->with_held_rate,
                                                    'bank_charge1' => $bankCharge,
                                                    'with_held_tax1' => $withHeldTax,
                                                    'tender_memo1' => $paymentData['payment_method'],
                                                    'net_credit1' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;

                                            case 2:  case "2":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account2' => $otherTender->bank_account,
                                                    'bank_account_number2' => $otherTender->bank_account_number,
                                                    'tender_type2' => "OTHERS",
                                                    'amount2' => $paymentData['amount'],
                                                    'tender_rate2' => $otherTender->tender_rate,
                                                    'with_held_rate2' => $otherTender->with_held_rate,
                                                    'bank_charge2' => $bankCharge,
                                                    'with_held_tax2' => $withHeldTax,
                                                    'tender_memo2' => $paymentData['payment_method'],
                                                    'net_credit2' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;

                                            case 3:  case "3":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account3' => $otherTender->bank_account,
                                                    'bank_account_number3' => $otherTender->bank_account_number,
                                                    'tender_type3' => "OTHERS",
                                                    'amount3' => $paymentData['amount'],
                                                    'tender_rate3' => $otherTender->tender_rate,
                                                    'with_held_rate3' => $otherTender->with_held_rate,
                                                    'bank_charge3' => $bankCharge,
                                                    'with_held_tax3' => $withHeldTax,
                                                    'tender_memo3' => $paymentData['payment_method'],
                                                    'net_credit3' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;
                                        }
                                    }

                                    elseif($paymentData['type'] == "CHARGE"){

                                        $otherTender = OtherTenderMaster::where('company_code',$salesRecord['pos_sale']->fcompanyid)
                                            ->where('branch_id',$salesRecord['pos_sale']->fofficeid)
                                            ->where('tender_id',$paymentData['payment_method'])
                                            ->leftJoin('bank_account_lines','other_tender_masters.bank_account_lines_id','=','bank_account_lines.id')
                                            ->first();

                                        $bankCharge = $paymentData['amount'] * $otherTender->tender_rate;
                                        $withHeldTax = $paymentData['amount'] * $otherTender->with_held_rate;

                                        switch($paymentData['sequence_number']){
                                            case 1: case "1":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account1' => $otherTender->bank_account,
                                                    'bank_account_number1' => $otherTender->bank_account_number,
                                                    'tender_type1' => "CHARGE",
                                                    'amount1' => $paymentData['amount'],
                                                    'tender_rate1' => $otherTender->tender_rate,
                                                    'with_held_rate1' => $otherTender->with_held_rate,
                                                    'bank_charge1' => $bankCharge,
                                                    'with_held_tax1' => $withHeldTax,
                                                    'tender_memo1' => $paymentData['payment_method'],
                                                    'net_credit1' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;

                                            case 2:  case "2":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account2' => $otherTender->bank_account,
                                                    'bank_account_number2' => $otherTender->bank_account_number,
                                                    'tender_type2' => "CHARGE",
                                                    'amount2' => $paymentData['amount'],
                                                    'tender_rate2' => $otherTender->tender_rate,
                                                    'with_held_rate2' => $otherTender->with_held_rate,
                                                    'bank_charge2' => $bankCharge,
                                                    'with_held_tax2' => $withHeldTax,
                                                    'tender_memo2' => $paymentData['payment_method'],
                                                    'net_credit2' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;

                                            case 3:  case "3":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account3' => $otherTender->bank_account,
                                                    'bank_account_number3' => $otherTender->bank_account_number,
                                                    'tender_type3' => "CHARGE",
                                                    'amount3' => $paymentData['amount'],
                                                    'tender_rate3' => $otherTender->tender_rate,
                                                    'with_held_rate3' => $otherTender->with_held_rate,
                                                    'bank_charge3' => $bankCharge,
                                                    'with_held_tax3' => $withHeldTax,
                                                    'tender_memo3' => $paymentData['payment_method'],
                                                    'net_credit3' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;
                                        }
                                    }

                                    elseif($paymentData['type'] == "CASH"){

                                        $cash = CashMaster::where('company_code',$salesRecord['pos_sale']->fcompanyid)
                                            ->where('branch_id',$salesRecord['pos_sale']->fofficeid)
                                            ->leftJoin('bank_account_lines','cash_masters.bank_account_lines_id','=','bank_account_lines.id')
                                            ->first();

                                        $bankCharge = $paymentData['amount'] * $cash->tender_rate;
                                        $withHeldTax = $paymentData['amount'] * $cash->with_held_rate;

                                        switch($paymentData['sequence_number']){
                                            case 1: case "1":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account1' => $cash->bank_account,
                                                    'bank_account_number1' => $cash->bank_account_number,
                                                    'tender_type1' => "CASH",
                                                    'amount1' => $paymentData['amount'],
                                                    'tender_rate1' => $cash->tender_rate,
                                                    'with_held_rate1' => $cash->with_held_rate,
                                                    'bank_charge1' => $bankCharge,
                                                    'with_held_tax1' => $withHeldTax,
                                                    'net_credit1' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;

                                            case 2:  case "2":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account2' => $cash->bank_account,
                                                    'bank_account_number2' => $cash->bank_account_number,
                                                    'tender_type2' => "CASH",
                                                    'amount2' => $paymentData['amount'],
                                                    'tender_rate2' => $cash->tender_rate,
                                                    'with_held_rate2' => $cash->with_held_rate,
                                                    'bank_charge2' => $bankCharge,
                                                    'with_held_tax2' => $withHeldTax,
                                                    'net_credit2' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;

                                            case 3:  case "3":

                                                SalesReport::where('id',$salesReportId->id)->update([
                                                    'bank_account3' => $cash->bank_account,
                                                    'bank_account_number3' => $cash->bank_account_number,
                                                    'tender_type3' => "CASH",
                                                    'amount3' => $paymentData['amount'],
                                                    'tender_rate3' => $cash->tender_rate,
                                                    'with_held_rate3' => $cash->with_held_rate,
                                                    'bank_charge3' => $bankCharge,
                                                    'with_held_tax3' => $withHeldTax,
                                                    'net_credit3' => $paymentData['amount'] - $bankCharge - $withHeldTax
                                                ]);

                                                break;
                                        }
                                    }

                                } catch (\Throwable $th) {
                                    continue;
                                }

                            }

                        }
                    }

                }

            }
            \Log::notice('done manual pull : daily sales by date');
        }


        public function searchSales(Request $request)
        {

            $data = [];
            $filters = [];
             $data['page_title'] = 'Sales Report';
             $data['companies'] = Company::where('status','ACTIVE')->orderBy('company_name','ASC')->get();
             $data['sources'] = SourceType::where('status','ACTIVE')->orderBy('source_name','ASC')->get();
             $salesReport = SalesReport::orderby('sales_reports.id','desc');

             if($request->datefrom != '' && $request->dateto != '' && !is_null($request->datefrom) && !is_null($request->dateto)){
                $salesReport->whereBetween('sales_reports.sales_trx_date',[$request->datefrom, $request->dateto]);
                $filters['filter_column[sales_reports.sales_trx_date][type]'] = 'between';
                $filters['filter_column[sales_reports.sales_trx_date][value][0]'] = $request->datefrom;
                $filters['filter_column[sales_reports.sales_trx_date][value][1]'] = $request->dateto;
             }

             if($request->company != '' && !is_null($request->company)){
                $salesReport->where('sales_reports.company',$request->company);
                $filters['filter_column[sales_reports.company][type]'] = '=';
                $filters['filter_column[sales_reports.company][value]'] = $request->company;
             }

             if($request->branch != '' && !is_null($request->branch)){
                $salesReport->where('sales_reports.branch',$request->branch);
                $filters['filter_column[sales_reports.branch][type]'] = '=';
                $filters['filter_column[sales_reports.branch][value]'] = $request->branch;
             }

             if($request->source != '' && !is_null($request->source)){
                $salesReport->where('sales_reports.source',$request->source);
                $filters['filter_column[sales_reports.source][type]'] = '=';
                $filters['filter_column[sales_reports.source][value]'] = $request->source;
             }

             if($request->receipt_number != '' && !is_null($request->receipt_number)){
                $salesReport->where('sales_reports.receipt_number',$request->receipt_number);
                $filters['filter_column[sales_reports.receipt_number][type]'] = '=';
                $filters['filter_column[sales_reports.receipt_number][value]'] = $request->receipt_number;
             }

             $salesReport->leftJoin('companies','sales_reports.company','=','companies.company_code')
             ->leftJoin('source_types','sales_reports.source','=','source_types.code')
             ->leftJoin('pos_branches', function($join){
                 $join->on('sales_reports.company', '=', 'pos_branches.company_id');
                 $join->on('sales_reports.branch','=','pos_branches.branch_id');
             })
             ->select(
                'sales_reports.id',
                'sales_reports.sales_trx_date',
                'sales_reports.sales_trx_time',
                'sales_reports.terminal_number',
                'companies.company_name',
                'sales_reports.receipt_number',
                'sales_reports.sale_memo',
                'source_types.source_name',
                'sales_reports.customer',
                'sales_reports.cashier',
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
                'sales_reports.trx_number'
             );

             $data['result'] = $salesReport->get();
             $data['filters'] = $filters;

             return view('sales-report.search-result',$data);
        }

        public function salesExport(Request $request)
		{
			$filename = $request->input('filename');
			return Excel::download(new SalesReportExport, $filename.'.xlsx');
		}

        public function tenderExport(Request $request)
		{
			$filename = $request->input('filename');
			return Excel::download(new SalesTenderExport, $filename.'.xlsx');
		}

        public function summaryExport(Request $request)
		{
			$filename = $request->input('filename');
			return Excel::download(new SalesSummaryExport, $filename.'.xlsx');
		}
	}
