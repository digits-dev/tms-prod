<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PosAccount;
use Illuminate\Http\Request;
use DB;
use CRUDBooster;

class PosAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PosAccount  $posAccount
     * @return \Illuminate\Http\Response
     */
    public function show(PosAccount $posAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PosAccount  $posAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(PosAccount $posAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PosAccount  $posAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PosAccount $posAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PosAccount  $posAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(PosAccount $posAccount)
    {
        //
    }

    public function getPosAccounts()
    {
        $companies = Company::where('status','ACTIVE')->orderBy('company_name','ASC')->get();

            foreach ($companies as $key => $value) {
                $account = DB::connection('mysql_tunnel')
                    ->table('mst_account')
                    ->where('fcompanyid',$value->company_code)
                    ->where('factive_flag',1)
                    ->get();

                ini_set('max_execution_time', '300');
                foreach ($account as $keyAccount => $valueAccount) {
                    PosAccount::firstOrCreate([
                        'company_code' => $valueAccount->fcompanyid,
                        'account_code' => $valueAccount->faccountid,
                        'account_name' => $valueAccount->fname,
                        'status' => 'ACTIVE'
                    ]);
                }

            }

            CRUDBooster::redirect(CRUDBooster::adminpath(),"Fetch all account from POS!","success");
    }
}
