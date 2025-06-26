<?php

namespace App\Http\Controllers;

use App\Models\BankMaster;
use App\Models\Company;
use App\Models\PosBranch;
use Illuminate\Http\Request;
use DB;

class BankMasterController extends Controller
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
     * @param  \App\Models\BankMaster  $bankMaster
     * @return \Illuminate\Http\Response
     */
    public function show(BankMaster $bankMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BankMaster  $bankMaster
     * @return \Illuminate\Http\Response
     */
    public function edit(BankMaster $bankMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BankMaster  $bankMaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankMaster $bankMaster)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BankMaster  $bankMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankMaster $bankMaster)
    {
        //
    }

    public function getBankMaster()
    {
        $companies = Company::where('status','ACTIVE')->orderBy('company_name','ASC')->get();

        foreach ($companies as $key => $value) {
            $dataBanks = DB::connection('mysql_tunnel')
                ->table('mst_bank')
                ->where('fcompanyid', $value->company_code)
                ->get();

            $branches = PosBranch::where('company_id',$value->company_code)
                ->where('status','ACTIVE')
                ->orderBy('branch_name','ASC')->get();

            foreach ($dataBanks as $keyBank => $valueBank) {
                foreach($branches as $keyBranch => $valueBranch){
                    BankMaster::firstOrCreate([
                        'company_code' => $valueBank->fcompanyid,
                        'branch_id' => $valueBranch->branch_id,
                        'bank_id' => $valueBank->fbankid
                    ],[
                        'company_code' => $valueBank->fcompanyid,
                        'branch_id' => $valueBranch->branch_id,
                        'bank_id' => $valueBank->fbankid,
                        'bank_name' => $valueBank->fname
                    ]);
                }

            }
        }
    }
}
