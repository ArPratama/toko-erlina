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
        $return = array('status'=>false,'UserID'=>"",'DistributorID'=>"");
        $query = "SELECT u.ID, u.DistributorID, u.AccountType
                    FROM MS_USER u
                        JOIN TR_SESSION s ON s.UserID = u.ID
                    WHERE s.Token=?
                        AND s.LogoutDate IS NULL";
        $checkAuth = DB::select($query,[$Token]);
        if ($checkAuth) {
            $data = $checkAuth[0];
            $query = "UPDATE TR_SESSION SET LastActive=GETDATE() WHERE Token=?";
            DB::update($query,[$Token]);
            $return = array(
                'status' => true,
                'UserID' => $data->ID,
                'AccountType' => $data->AccountType,
                'DistributorID' => $data->DistributorID
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
            $mainQuery = "SELECT i.DistributorID,
                            i.IncomingDate,
                            d.Name Distributor,
                            i.DepoID,
                            de.Name Depo,
                            p.SKU,
                            i.IMEI,
                            p.Name Product,
                            p.Description,
                            p.Color,
                            p.Capacity,
                            COUNT(p.SKU) Total,
                            CASE
                                WHEN r1.Field1 = 'PRICECAT1' THEN p.UnitPrice
                                WHEN r1.Field1 = 'PRICECAT2' THEN p.UnitPriceAFP
                                WHEN r1.Field1 = 'PRICECAT3' THEN p.UnitPriceFTZ
                            END UnitPrice,
                            de.PriceType,
                            r1.Field2 PriceTypeDesc,
                            CASE
                                WHEN r1.Field1 = 'PRICECAT1' THEN p.InPrice
                                WHEN r1.Field1 = 'PRICECAT2' THEN p.InPriceAFP
                                WHEN r1.Field1 = 'PRICECAT3' THEN p.InPriceFTZ
                            END InPrice
                        FROM MS_INVENTORY i
                            JOIN MS_PRODUCT p ON p.ID=i.ProductID
                            LEFT JOIN MS_DISTRIBUTOR d ON d.ID=i.DistributorID
                            LEFT JOIN MS_DEPO de ON de.ID=i.DepoID
                            LEFT JOIN MS_REFERENCES r1 ON r1.ID = de.PriceType
                        WHERE {definedFilter}
                        GROUP BY i.DistributorID,i.IncomingDate,d.Name,i.DepoID,i.IMEI,de.Name,p.SKU,p.Name,p.Description,p.Color,p.Capacity,p.UnitPrice,p.UnitPriceAFP,p.UnitPriceFTZ, de.PriceType, r1.Field2, r1.Field1, p.InPrice, p.InPriceAFP, p.InPriceFTZ
                        ORDER BY Total DESC";
            $definedFilter = "i.Status IN ('1','2','3')";
            if ($request->imei) $definedFilter .= " AND i.IMEI=? ";
            if ($request->startIncomingDate) $definedFilter.= " AND i.IncomingDate > '".$request->startIncomingDate."'";
            if ($request->endIncomingDate) $definedFilter.= " AND DATEADD(day, -1, i.IncomingDate) <= '".$request->endIncomingDate."'";
            if ($getAuth['AccountType']==1) {
                $query = str_replace("{definedFilter}",$definedFilter,$mainQuery);
                if ($request->imei) {
                $data = DB::select($query, [$request->imei]);
                } else {
                $data = DB::select($query);
                }
            } else {
                $definedFilter .= " AND i.DistributorID=? ";
                $query = str_replace("{definedFilter}",$definedFilter,$mainQuery);
                if ($request->imei) {
                $data = DB::select($query,[$request->imei, $getAuth['DistributorID']]);
                } else {
                $data = DB::select($query,[$getAuth['DistributorID']]);
                }
            }
            if ($data) $return['data'] = $data;
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        if (!$request->_export) {
            return response()->json($return, 200);
        } else {
            $filename = 'Stock_on_Hand';
            $arrData = [];
            $arrHeader = array(
                "INCOMING DATE",
                "DISTRIBUTOR ID",
                "DISTRIBUTOR",
                "DEPO ID",
                "DEPO",
                "SKU",
                "IMEI",
                "PRODUCT NAME",
                "DESCRIPTION",
                "COLOR",
                "CAPACITY",
                "QUANTITY",
                "PRICE (RRP)",
                "PRICE TYPE",
                "SEGMENT"
            );
            array_push($arrData,$arrHeader);
            foreach ($return['data'] as $key => $value) {
                $segment = 'PREMIUM';
                if ($value->InPrice < 9000000) $segment = 'HIGH';
                if ($value->InPrice < 6000000) $segment = 'MID';
                if ($value->InPrice < 4500000) $segment = 'MASS';
                if ($value->InPrice < 3000000) $segment = 'ENTRY';
                if ($value->InPrice < 1500000) $segment = 'ULC';
                $rows = array(
                    $value->IncomingDate,
                    $value->DistributorID,
                    $value->Distributor,
                    $value->DepoID,
                    $value->Depo,
                    $value->SKU,
                    $value->IMEI,
                    $value->Product,
                    $value->Description,
                    $value->Color,
                    $value->Capacity,
                    $value->Total,
                    $value->UnitPrice,
                    $value->PriceTypeDesc,
                    $segment
                );
                array_push($arrData,$rows);
            }
            return Excel::download(new GeneralExport([$arrData]), $filename.'_'.time().'.xlsx');
        }
    }

    public function getPurchasing(Request $request)
    {
        $return = array('status'=>true,'message'=>"",'data'=>array(),'callback'=>"");
        $getAuth = $this->validateAuth($request->_s);
        if ($getAuth['status']) {
            $mainQuery = "SELECT p.SKU,
                            i.IMEI,
                            p.Name Product,
                            p.Description,
                            p.Color,
                            p.Capacity,
                            COUNT(p.SKU) Total,
                            MAX(i.BookedDate) LastBook
                        FROM MS_INVENTORY i
                            JOIN MS_PRODUCT p ON p.ID=i.ProductID
                        WHERE {definedFilter}
                        GROUP BY p.SKU,p.Name,p.Description,p.Color,p.Capacity,i.IMEI
                        {havingFilter}
                        ORDER BY Total DESC";
            $definedFilter = "i.DeliveryOrderID != ''";
            $havingFilter = "";
            if ($request->imei) $definedFilter .= " AND i.IMEI=? ";
            if ($request->startOrderDate || $request->endOrderDate) $havingFilter .= "HAVING MAX(i.BookedDate) != ''";
            if ($request->startOrderDate) $havingFilter.= " AND MAX(i.BookedDate) > '".$request->startOrderDate."'";
            if ($request->endOrderDate) $havingFilter.= " AND DATEADD(day, -1, MAX(i.BookedDate)) <= '".$request->endOrderDate."'";
            $mainQuery = str_replace("{havingFilter}",$havingFilter,$mainQuery);
            if ($getAuth['AccountType']==1) {
                $query = str_replace("{definedFilter}",$definedFilter,$mainQuery);
                if ($request->imei) {
                $data = DB::select($query, [$request->imei]);
                } else {
                $data = DB::select($query);
                }
            } else {
                $definedFilter .= " AND i.DistributorID=? ";
                $query = str_replace("{definedFilter}",$definedFilter,$mainQuery);
                if ($request->imei) {
                $data = DB::select($query,[$request->imei, $getAuth['DistributorID']]);
                } else {
                $data = DB::select($query,[$getAuth['DistributorID']]);
                }
            }
            if ($data) $return['data'] = $data;
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        if (!$request->_export) {
            return response()->json($return, 200);
        } else {
            $filename = 'Sales_by_Model';
            $arrData = [];
            $arrHeader = array(
                "SKU",
                "IMEI",
                "PRODUCT NAME",
                "DESCRIPTION",
                "COLOR",
                "CAPACITY",
                "ITEM SOLD",
                "LAST ORDER"
            );
            array_push($arrData,$arrHeader);
            foreach ($return['data'] as $key => $value) {
                $rows = array(
                    $value->SKU,
                    $value->IMEI,
                    $value->Product,
                    $value->Description,
                    $value->Color,
                    $value->Capacity,
                    $value->Total,
                    $value->LastBook
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
