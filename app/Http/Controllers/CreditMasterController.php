<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CreditMaster;
use App\Models\PosBranch;
use Illuminate\Http\Request;
use DB;

class CreditMasterController extends Controller
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
     * @param  \App\Models\CreditMaster  $creditMaster
     * @return \Illuminate\Http\Response
     */
    public function show(CreditMaster $creditMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CreditMaster  $creditMaster
     * @return \Illuminate\Http\Response
     */
    public function edit(CreditMaster $creditMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CreditMaster  $creditMaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CreditMaster $creditMaster)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CreditMaster  $creditMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy(CreditMaster $creditMaster)
    {
        //
    }

    public function getCreditMaster()
    {
        $companies = Company::where('status','ACTIVE')->orderBy('company_name','ASC')->get();

        foreach ($companies as $key => $value) {
            $dataCredit = DB::connection('mysql_tunnel')
                ->table('mst_credit')
                ->where('fcompanyid', $value->company_code)
                ->get();

            $branches = PosBranch::where('company_id',$value->company_code)
                ->where('status','ACTIVE')
                ->orderBy('branch_name','ASC')->get();

            foreach ($dataCredit as $keyCredit => $valueCredit) {
                foreach($branches as $keyBranch => $valueBranch){
                    CreditMaster::firstOrCreate([
                        'company_code' => $valueCredit->fcompanyid,
                        'branch_id' => $valueBranch->branch_id,
                        'credit_id' => $valueCredit->fcreditid
                    ],[
                        'company_code' => $valueCredit->fcompanyid,
                        'branch_id' => $valueBranch->branch_id,
                        'credit_id' => $valueCredit->fcreditid,
                        'credit_name' => $valueCredit->fname
                    ]);
                }
            }
        }
    }
}
