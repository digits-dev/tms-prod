<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\OtherTenderMaster;
use App\Models\PosBranch;
use Illuminate\Http\Request;
use DB;

class OtherTenderMasterController extends Controller
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
     * @param  \App\Models\OtherTenderMaster  $otherTenderMaster
     * @return \Illuminate\Http\Response
     */
    public function show(OtherTenderMaster $otherTenderMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OtherTenderMaster  $otherTenderMaster
     * @return \Illuminate\Http\Response
     */
    public function edit(OtherTenderMaster $otherTenderMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OtherTenderMaster  $otherTenderMaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OtherTenderMaster $otherTenderMaster)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OtherTenderMaster  $otherTenderMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy(OtherTenderMaster $otherTenderMaster)
    {
        //
    }

    public function getTenderMaster()
    {
        $companies = Company::where('status','ACTIVE')->orderBy('company_name','ASC')->get();

        foreach ($companies as $key => $value) {
            $dataTenders = DB::connection('mysql_tunnel')
                ->table('mst_tender_type')
                ->where('fcompanyid', $value->company_code)
                ->get();

            $branches = PosBranch::where('company_id',$value->company_code)
                ->where('status','ACTIVE')
                ->orderBy('branch_name','ASC')->get();

            foreach ($dataTenders as $keyTender => $valueTender) {
                foreach($branches as $keyBranch => $valueBranch){
                    OtherTenderMaster::firstOrCreate([
                        'company_code' => $valueTender->fcompanyid,
                        'branch_id' => $valueBranch->branch_id,
                        'tender_id' => $valueTender->ftenderid
                    ],[
                        'company_code' => $valueTender->fcompanyid,
                        'branch_id' => $valueBranch->branch_id,
                        'tender_id' => $valueTender->ftenderid,
                        'tender_name' => $valueTender->fname
                    ]);
                }
            }
        }
    }
}
