<?php

namespace App\Http\Controllers;

use App\Models\SalesReport;
use App\Models\SalesReportLine;
use Illuminate\Http\Request;
use DB;
use PDO;
use CRUDBooster;

class POSPullController extends Controller
{

    private $client;

    public function __construct()
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        $this->client = new \SoapClient("http://bc.alliancewebpos.com/appserv/app/w3p/w3p.wsdl",
            array("location" => "http://bc.alliancewebpos.com/appserv/app/w3p/W3PSoapServer.php"));
    }

    public function getProduct($item)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>".config('w3p.fw3p_id')."</fw3p_id>
                    <fw3p_key>".config('w3p.fw3p_key')."</fw3p_key>
                </id>

                <data>
                    <filter>
                        <fproductid>".$item."</fproductid>
                        <fthirdpartyid></fthirdpartyid>
                        <fkeyword></fkeyword>
                    </filter>
                </data>
            </root>";

        $result = $this->client->call("GET_PRODUCT",$parameter);
        return json_decode(json_encode(simplexml_load_string($result)), true);

    }

    public function getSales()
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>".config('w3p.fw3p_id')."</fw3p_id>
                    <fw3p_key>".config('w3p.fw3p_key')."</fw3p_key>
                </id>

                <data>
                    <filter>
                        <ftermid>0013</ftermid>
                        <ffrom>20211201</ffrom>
                        <fto>20211201</fto>
                        <fzcounter></fzcounter>
                        <fper_customer_flag>0</fper_customer_flag>
                    </filter>
                </data>
            </root>";

        $result = $this->client->call("GET_SALES",$parameter);
        return json_decode(json_encode(simplexml_load_string($result)), true);

    }

    public function getSalesTransaction()
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>".config('w3p.fw3p_id')."</fw3p_id>
                    <fw3p_key>".config('w3p.fw3p_key')."</fw3p_key>
                </id>

                <data>
                    <filter>
                        <ftermid>0013</ftermid>
                        <ffrom>20211201</ffrom>
                        <fto>20211201</fto>
                        <fzcounter></fzcounter>
                        <faccountid></faccountid>
                        <fdocument_no></fdocument_no>
                        <fexclude_return>1</fexclude_return>
                        <fexclude_void>1</fexclude_void>
                    </filter>
                </data>
            </root>";

        $result = $this->client->call("GET_SALES_TRANSACTION",$parameter);
        return json_decode(json_encode(simplexml_load_string($result)), true);
    }

    public function getSalesTransactionByDate($datefrom, $dateto, $terminal)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>".config('w3p.fw3p_id')."</fw3p_id>
                    <fw3p_key>".config('w3p.fw3p_key')."</fw3p_key>
                </id>

                <data>
                    <filter>
                        <ftermid>".$terminal."</ftermid>
                        <ffrom>".$datefrom."</ffrom>
                        <fto>".$dateto."</fto>
                        <fzcounter></fzcounter>
                        <faccountid></faccountid>
                        <fdocument_no></fdocument_no>
                        <fexclude_return>1</fexclude_return>
                        <fexclude_void>1</fexclude_void>
                    </filter>
                </data>
            </root>";

        $result = $this->client->call("GET_SALES_TRANSACTION",$parameter);
        return json_decode(json_encode(simplexml_load_string($result)), true);
    }

    public function getSalesTransactionByTerminalDate($datefrom, $dateto, $terminal, $company)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>".$company."</fw3p_id>
                    <fw3p_key>".config('w3p.fw3p_key')."</fw3p_key>
                </id>

                <data>
                    <filter>
                        <ftermid>".$terminal."</ftermid>
                        <ffrom>".$datefrom."</ffrom>
                        <fto>".$dateto."</fto>
                        <fzcounter></fzcounter>
                        <faccountid></faccountid>
                        <fdocument_no></fdocument_no>
                        <fexclude_return>1</fexclude_return>
                        <fexclude_void>1</fexclude_void>
                        <fnew_batchid></fnew_batchid>
                        <flast_key></flast_key>
                    </filter>
                </data>
            </root>";

        $result = $this->client->call("GET_SALES_TRANSACTION",$parameter);
        return json_decode(json_encode(simplexml_load_string($result)), true);
    }

    public function getVoidSalesTransactionByTerminalDate($datefrom, $dateto, $terminal, $company)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>".$company."</fw3p_id>
                    <fw3p_key>".config('w3p.fw3p_key')."</fw3p_key>
                </id>

                <data>
                    <filter>
                        <ftermid>".$terminal."</ftermid>
                        <ffrom>".$datefrom."</ffrom>
                        <fto>".$dateto."</fto>
                        <fzcounter></fzcounter>
                        <faccountid></faccountid>
                        <fdocument_no></fdocument_no>
                        <fexclude_return>1</fexclude_return>
                        <fexclude_void>0</fexclude_void>
                    </filter>
                </data>
            </root>";

        $result = $this->client->call("GET_SALES_TRANSACTION",$parameter);
        return json_decode(json_encode(simplexml_load_string($result)), true);
    }

    public function getSalesRecord($receipt, $terminal)
    {
        $data['pos_sale'] = DB::connection('mysql_tunnel')
            ->table('pos_sale')
            ->where('ftermid',$terminal)
            ->where('fcompanyid','BC-'.config('w3p.fw3p_id'))
            ->where('fdocument_no',$receipt)
            ->first();

        $data['pos_sale_product'] = DB::connection('mysql_tunnel')
            ->table('pos_sale_product')
            ->where('ftermid',$terminal)
            ->where('fcompanyid','BC-'.config('w3p.fw3p_id'))
            ->where('frecno',$data['pos_sale']->frecno)
            ->get();

        foreach($data['pos_sale_product'] as $key => $value){
            if(!is_null($value->fpromoid || $value->fpromoid != "")){
                $data['pos_sale_product_discount'][$value->fproductid] = DB::connection('mysql_tunnel')
                    ->table('mst_price')
                    ->where('fpriceid',$value->fpromoid)
                    ->first();
                }
        }

        $data['pos_sale_discount'] = DB::connection('mysql_tunnel')
            ->table('pos_sale_product_discount')
            ->where('ftermid',$terminal)
            ->where('fcompanyid','BC-'.config('w3p.fw3p_id'))
            ->where('frecno',$data['pos_sale']->frecno)
            ->first();

        $data['pos_sale_discount_detail'] = DB::connection('mysql_tunnel')
            ->table('mst_discount')
            ->where('fdiscountid',$data['pos_sale_discount']->fdiscountid)
            ->where('fcompanyid','BC-'.config('w3p.fw3p_id'))
            ->first();

        $data['pos_sale_info'] = DB::connection('mysql_tunnel')
            ->table('pos_sale_info')
            ->where('ftermid',$terminal)
            ->where('fcompanyid','BC-'.config('w3p.fw3p_id'))
            ->where('frecno',$data['pos_sale']->frecno)
            ->first();

        return $data;
    }

    public function getSalesRecordByTerminal($receipt, $company, $terminal)
    {
        $data['pos_sale'] = DB::connection('mysql_tunnel')
            ->table('pos_sale')
            ->where('ftermid',$terminal)
            ->where('fcompanyid',$company)
            ->where('fdocument_no',$receipt)
            ->first();

        $data['pos_sale_product'] = DB::connection('mysql_tunnel')
            ->table('pos_sale_product')
            ->where('ftermid',$terminal)
            ->where('fcompanyid',$company)
            ->where('frecno',$data['pos_sale']->frecno)
            ->get();

        foreach($data['pos_sale_product'] as $key => $value){
            if(!is_null($value->fpromoid || $value->fpromoid != "")){
            $data['pos_sale_product_discount'][$value->fproductid] = DB::connection('mysql_tunnel')
                ->table('mst_price')
                ->where('fpriceid',$value->fpromoid)
                ->first();
            }
        }

        $data['pos_sale_discount'] = DB::connection('mysql_tunnel')
            ->table('pos_sale_product_discount')
            ->where('ftermid',$terminal)
            ->where('fcompanyid',$company)
            ->where('frecno',$data['pos_sale']->frecno)
            ->first();

        $data['pos_sale_discount_detail'] = DB::connection('mysql_tunnel')
            ->table('mst_discount')
            ->where('fdiscountid',$data['pos_sale_discount']->fdiscountid)
            ->where('fcompanyid',$company)
            ->first();

        $data['pos_sale_info'] = DB::connection('mysql_tunnel')
            ->table('pos_sale_info')
            ->where('ftermid',$terminal)
            ->where('fcompanyid',$company)
            ->where('frecno',$data['pos_sale']->frecno)
            ->first();

        $data['pos_sale_payment'] = DB::connection('mysql_tunnel')
            ->table('pos_sale_payment')
            ->where('ftermid',$terminal)
            ->where('fcompanyid',$company)
            ->where('frecno',$data['pos_sale']->frecno)
            ->where('fsale_date',$data['pos_sale']->fsale_date)
            ->get();

        return $data;
    }


}
