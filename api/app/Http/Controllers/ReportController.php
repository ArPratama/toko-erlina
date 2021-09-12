<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Exports\GeneralExport;
use App\Imports\GeneralImport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    private function validateAuth($Token) {
        $return = array('status'=>false,'UserID'=>"");
        $query = "SELECT u.ID, u.AccountType
                    FROM MS_USER u
                        JOIN TR_SESSION s ON s.UserID = u.ID
                    WHERE s.Token=?
                        AND s.LogoutDate IS NULL";
        $checkAuth = DB::select($query,[$Token]);
        if ($checkAuth) {
            $data = $checkAuth[0];
            $query = "UPDATE TR_SESSION SET LastActive=NOW() WHERE Token=?";
            DB::update($query,[$Token]);
            $return = array(
                'status' => true,
                'UserID' => $data->ID,
                'AccountType' => $data->AccountType
            );
        }
        return $return;
    }

    public function getStock(Request $request)
    {
        ini_set('memory_limit', '-1');
        $return = array('status'=>true,'message'=>"",'data'=>array(),'callback'=>"");
        $getAuth = $this->validateAuth($request->_s);
        if ($getAuth['status']) {
            $mainQuery = "SELECT s.ID,
                            s.ProductID,
                            s.CreatedDate,
                            s.Amount,
                            s.Status,
                            s.Source,
                            s.IncomingDate,
                            p.Name ProductName
                        FROM ms_stock s
                        JOIN ms_product p ON p.ID = s.ProductID
                        WHERE {definedFilter}
                        ORDER BY IncomingDate DESC";
            $definedFilter = "s.Status IN ('1','2','3')";
            if ($request->productID) $definedFilter .= " AND s.ProductID=? ";
            if ($request->startIncomingDate) $definedFilter.= " AND s.IncomingDate >= '".$request->startIncomingDate."'";
            if ($request->endIncomingDate) $definedFilter.= " AND DATE_ADD(s.IncomingDate, INTERVAL -1 DAY) < '".$request->endIncomingDate."'";
            $query = str_replace("{definedFilter}",$definedFilter,$mainQuery);
            if ($request->productID) {
                $data = DB::select($query, [$request->productID]);
            } else {
                $data = DB::select($query);
            }
            if ($data) $return['data'] = $data;
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        if (!$request->_export) {
            return response()->json($return, 200);
        } else {
            $filename = 'Stock_Reporting';
            $arrData = [];
            $arrHeader = array(
                "INCOMING DATE",
                "PRODUCT NAME",
                "AMOUNT",
                "STATUS",
                "SOURCE",
            );
            array_push($arrData,$arrHeader);
            foreach ($return['data'] as $key => $value) {
                $status = 'On Display';
                if ($value->Status == 2) $status = 'On Storage';
                $rows = array(
                    $value->IncomingDate,
                    $value->ProductName,
                    $value->Amount,
                    $status,
                    $value->Source
                );
                array_push($arrData,$rows);
            }
            return Excel::download(new GeneralExport([$arrData]), $filename.'_'.time().'.xlsx');
        }
    }

    public function getPurchasing(Request $request)
    {
        ini_set('memory_limit', '-1');
        $return = array('status'=>true,'message'=>"",'data'=>array(),'callback'=>"");
        $getAuth = $this->validateAuth($request->_s);
        if ($getAuth['status']) {
            $mainQuery = "SELECT t.ID,
                t.TransactionDate,
                t.Status,
                cs.Name CustomerName,
                t.Description,
                SUM(td.Amount) TotalItem,
                SUM(td.Price * td.Amount) TotalPrice
                FROM tr_transaction t
                LEFT JOIN ms_customer cs ON cs.ID = t.CustomerID
                LEFT JOIN tr_transaction_detail td ON td.TransactionID =  t.ID
                WHERE {definedFilter}
                GROUP BY t.ID,
                t.TransactionDate,
                t.Status,
                cs.Name,
                t.Description
                ORDER BY t.TransactionDate DESC";
            $definedFilter = "t.Status IN ('1','2','3')";
            if ($request->startTransactionDate) $definedFilter.= " AND t.TransactionDate >= '".$request->startTransactionDate."'";
            if ($request->endTransactionDate) $definedFilter.= " AND DATE_ADD(t.TransactionDate, INTERVAL -1 DAY) < '".$request->endTransactionDate."'";

            $query = str_replace("{definedFilter}",$definedFilter,$mainQuery);
            $data = DB::select($query);

            if ($data) $return['data'] = $data;
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        if (!$request->_export) {
            return response()->json($return, 200);
        } else {
            $filename = 'Transaction_Reporting';
            $arrData = [];
            $arrHeader = array(
                "TRANSACTION DATE",
                "STATUS",
                "CUSTOMER NAME",
                "DESCRIPTION",
                "TOTAL ITEM",
                "TOTAL PRICE"
            );
            array_push($arrData,$arrHeader);
            foreach ($return['data'] as $key => $value) {
                $status = 'Active';
                if ($value->Status == 2) $status = 'Inactive';
                $rows = array(
                    $value->TransactionDate,
                    $status,
                    $value->CustomerName,
                    $value->Description,
                    $value->TotalItem,
                    $value->TotalPrice
                );
                array_push($arrData,$rows);
            }
            return Excel::download(new GeneralExport([$arrData]), $filename.'_'.time().'.xlsx');
        }
    }

    public function getCustomer(Request $request)
    {
        $return = array('status'=>true,'message'=>"",'data'=>array(),'callback'=>"");
        $getAuth = $this->validateAuth($request->_s);
        if ($getAuth['status']) {
            $mainQuery = "SELECT im.MutationDate,
                                i.DistributorID,
                                d.Name Distributor,
                                i.IMEI,
                                p.SKU,
                                p.Name Product,
                                p.Description,
                                p.Color,
                                p.Capacity,
                                ISNULL(im.OriginID,i.DistributorID) OriginID,
                                ISNULL(deA.Name,d.Name) Origin,
                                im.DepoID DestinationID,
                                deB.Name Destination,
                                im.Sequence
                            FROM TR_INVENTORY_MUTATION im
                                JOIN MS_INVENTORY i ON i.ID=im.InventoryID
                                JOIN MS_PRODUCT p ON p.ID=i.ProductID
                                LEFT JOIN MS_DISTRIBUTOR d ON d.ID=i.DistributorID
                                LEFT JOIN MS_DEPO deA ON deA.ID=im.OriginID
                                LEFT JOIN MS_DEPO deB ON deB.ID=im.DepoID
                            WHERE {definedFilter}
                            ORDER BY im.MutationDate DESC";
            $definedFilter = "1=1";
            if ($request->startMutationDate) $definedFilter.= " AND im.MutationDate > '".$request->startMutationDate."'";
            if ($request->endMutationDate) $definedFilter.= " AND DATEADD(day, -1, im.MutationDate) <= '".$request->endMutationDate."'";
            if ($getAuth['AccountType']==2) {
                $definedFilter .= " AND i.DistributorID='".$getAuth['DistributorID']."'";
            }
            $query = str_replace("{definedFilter}",$definedFilter,$mainQuery);
            $data = DB::select($query);
            if ($data) $return['data'] = $data;
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        if (!$request->_export) {
            return response()->json($return, 200);
        } else {
            $filename = 'Mutation_Report';
            $arrData = [];
            $arrHeader = array(
                "MUTATION DATE",
                "DISTRIBUTOR ID",
                "DISTRIBUTOR",
                "IMEI",
                "SKU",
                "PRODUCT",
                "DESCRIPTION",
                "COLOR",
                "CAPACITY",
                "ORIGIN ID",
                "ORIGIN",
                "DESTINATION ID",
                "DESTINATION",
            );
            array_push($arrData,$arrHeader);
            foreach ($return['data'] as $key => $value) {
                $rows = array(
                    $value->MutationDate,
                    $value->DistributorID,
                    $value->Distributor,
                    $value->IMEI,
                    $value->SKU,
                    $value->Product,
                    $value->Description,
                    $value->Color,
                    $value->Capacity,
                    $value->OriginID,
                    $value->Origin,
                    $value->DestinationID,
                    $value->Destination
                );
                array_push($arrData,$rows);
            }
            return Excel::download(new GeneralExport([$arrData]), $filename.'_'.time().'.xlsx');
        }
    }
}
