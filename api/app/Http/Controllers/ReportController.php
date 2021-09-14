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
                    FROM ms_user u
                        JOIN tr_session s ON s.UserID = u.ID
                    WHERE s.Token=?
                        AND s.LogoutDate IS NULL";
        $checkAuth = DB::select($query,[$Token]);
        if ($checkAuth) {
            $data = $checkAuth[0];
            $query = "UPDATE tr_session SET LastActive=NOW() WHERE Token=?";
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
            if ($request->_export) {
                $mainQuery = "SELECT t.TransactionDate,
                c.Name CustomerName,
                t.Description,
                p.Name ProductName,
                td.Price,
                td.Amount,
                (td.Price * td.Amount) Total,
                (SELECT SUM(Amount) FROM tr_transaction_detail td2 WHERE td2.TransactionID = t.ID) TotalItem,
                (SELECT SUM((td2.Price * td2.Amount)) FROM tr_transaction_detail td2 WHERE td2.TransactionID = t.ID) TotalPrice
                FROM tr_transaction_detail td
                LEFT JOIN ms_product p ON p.ID = td.ProductID
                LEFT JOIN tr_transaction t ON t.ID = td.TransactionID
                LEFT JOIN ms_customer c ON c.ID = t.CustomerID
                WHERE {definedFilter}
                ORDER BY t.TransactionDate DESC";
            }

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
                "CUSTOMER NAME",
                "DESCRIPTION",
                "PRODUCT NAME",
                "PRICE",
                "AMOUNT",
                "TOTAL",
                "TOTAL ITEM",
                "TOTAL PRICE"
            );
            array_push($arrData,$arrHeader);
            foreach ($return['data'] as $key => $value) {
                $rows = array(
                    $value->TransactionDate,
                    $value->CustomerName,
                    $value->Description,
                    $value->ProductName,
                    $value->Price,
                    $value->Amount,
                    $value->Total,
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
        ini_set('memory_limit', '-1');
        $return = array('status'=>true,'message'=>"",'data'=>array(),'callback'=>"");
        $getAuth = $this->validateAuth($request->_s);
        if ($getAuth['status']) {
            $mainQuery = "SELECT ID,
                            Name,
                            LastPurchase,
                            CreatedDate,
                            CreatedBy,
                            ModifiedDate,
                            ModifiedBy,
                            Status,
                            PurchaseAmount
                            FROM ms_customer
                            WHERE {definedFilter}
                            ORDER BY LastPurchase DESC";
            $definedFilter = "1=1";
            if ($request->startLastPurchaseDate) $definedFilter.= " AND LastPurchase >= '".$request->startLastPurchaseDate."'";
            if ($request->endLastPurchaseDate) $definedFilter.= " AND DATE_ADD(LastPurchase, INTERVAL -1 DAY) < '".$request->endLastPurchaseDate."'";

            $query = str_replace("{definedFilter}",$definedFilter,$mainQuery);
            $data = DB::select($query);

            if ($data) $return['data'] = $data;
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        if (!$request->_export) {
            return response()->json($return, 200);
        } else {
            $filename = 'Customer_Reporting';
            $arrData = [];
            $arrHeader = array(
                "LAST PURCHASE",
                "CUSTOMER NAME",
                "PURCHASE AMOUNT",
                "STATUS"
            );
            array_push($arrData,$arrHeader);
            foreach ($return['data'] as $key => $value) {
                $status = 'Active';
                if ($value->Status == 2) $status = 'Inactive';
                $rows = array(
                    $value->LastPurchase,
                    $value->Name,
                    $value->PurchaseAmount,
                    $status
                );
                array_push($arrData,$rows);
            }
            return Excel::download(new GeneralExport([$arrData]), $filename.'_'.time().'.xlsx');
        }
    }
}
