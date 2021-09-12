<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Exports\GeneralExport;
use App\Imports\GeneralImport;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    private function sanitizeString($string) {
        $string = trim($string);
        $string = str_replace("'","",$string);
        return $string;
    }

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

    public function getAll(Request $request)
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
            ORDER BY t.CreatedDate DESC
            {Limit}";
            $definedFilter = "t.Status IN (1,2)";
            if ($request->_i) {
                $definedFilter = "t.ID=?";
                $mainQuery = str_replace("{Limit}",'',$mainQuery);
                $query = str_replace("{definedFilter}",$definedFilter,$mainQuery);
                $data = DB::select($query,[$request->_i]);
                if ($data) {
                    $return['data'] = $data[0];
                    $return['callback'] = "onCompleteFetch(e.data)";
                }
            } else {
                if (!$request->_export) {
                    if ($request->columns[0]['search']['value'] != '') $definedFilter.= " AND t.TransactionDate LIKE '%".$this->sanitizeString($request->columns[0]['search']['value'])."%' ";
                    if ($request->columns[1]['search']['value'] != '') $definedFilter.= " AND t.Status LIKE '%".$this->sanitizeString($request->columns[1]['search']['value'])."%' ";
                    if ($request->columns[2]['search']['value'] != '') $definedFilter.= " AND cs.Name LIKE '%".$this->sanitizeString($request->columns[2]['search']['value'])."%' ";

                    $total = 0;
                    $mainQueryTotal = "SELECT COUNT(t.ID) Total
                                        FROM tr_transaction t
                                        WHERE {definedFilter}";
                    $query = str_replace("{definedFilter}",$definedFilter,$mainQueryTotal);
                    $data = DB::select($query);
                    $total = $data[0]->Total;

                    if ($request->start!='') $mainQuery = str_replace("{Limit}","LIMIT 10 OFFSET ".$request->start ,$mainQuery);
                    else $mainQuery = str_replace("{Limit}","",$mainQuery);

                    $query = str_replace("{definedFilter}",$definedFilter,$mainQuery);
                    $data = DB::select($query);

                    if ($data) {
                        $return['draw'] = $request->draw;
                        $return['recordsTotal'] = $total;
                        $return['recordsFiltered'] = $total;
                        $return['data'] = $data;
                    } else {
                        $return['draw'] = 1;
                        $return['recordsTotal'] = 0;
                        $return['recordsFiltered'] = 0;
                    }
                } else {
                    $mainQuery = str_replace("{Limit}",'',$mainQuery);
                    $query = str_replace("{definedFilter}",$definedFilter,$mainQuery);
                    $data = DB::select($query);
                    if ($data) $return['data'] = $data;
                }
            }
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        if (!$request->_export) {
            return response()->json($return, 200);
        } else {
            $filename = 'Transaction_Management';
            $arrData = [];
            $arrHeader = array(
                "ITEM ID",
                "TRANSACTION DATE",
                "STATUS",
                "CUSTOMER NAME",
                "DESCRIPTION",
            );
            array_push($arrData,$arrHeader);
            foreach ($return['data'] as $key => $value) {
                $rows = array(
                    $value->ID,
                    $value->TransactionDate,
                    $value->Status,
                    $value->CustomerName,
                    $value->Description
                );
                array_push($arrData,$rows);
            }
            return Excel::download(new GeneralExport([$arrData]), $filename.'_'.time().'.xlsx');
        }
    }

    public function doSave(Request $request)
    {
        $return = array('status'=>true,'message'=>"",'data'=>null,'callback'=>"");
        $getAuth = $this->validateAuth($request->_s);
        if ($getAuth['status']) {
            if ($request->hdnFrmAction=="add") {
                $query = "INSERT INTO tr_transaction
                            (ID, TransactionDate, Status, Description, CustomerID, CreatedDate, CreatedBy, ModifiedDate, ModifiedBy)
                            VALUES(UUID(), ?, ?, ?, ?, NOW(), ?, NULL, NULL)";
                DB::insert($query, [
                    $request->txtFrmTransactionDate,
                    $request->radFrmStatus,
                    $request->txtFrmDescription,
                    $request->txtFrmCustomerID,
                    $getAuth['UserID']
                ]);

                //Update Last Purchase Customer
                $query = "SELECT ID, PurchaseAmount FROM ms_customer WHERE ID = ?";
                $refDataCustomer = DB::select($query, [$request->txtFrmCustomerID]);

                if($refDataCustomer){
                    $newPurchaseAmount = $refDataCustomer[0]->PurchaseAmount + 1;
                    $query = "UPDATE ms_customer SET
                                    LastPurchase=?,
                                    PurchaseAmount=?,
                                    ModifiedDate=NOW(),
                                    ModifiedBy=?
                                WHERE ID=?";
                    DB::update($query, [
                        $request->txtFrmTransactionDate,
                        $newPurchaseAmount,
                        $getAuth['UserID'],
                        $request->txtFrmCustomerID
                    ]);
                }
                $return['message'] = "Data has been saved!";
                $return['callback'] = "doReloadTable()";
            }
            if ($request->hdnFrmAction=="edit") {
                $query = "UPDATE tr_transaction
                            SET CustomerID=?
                                ModifiedDate=NOW(),
                                ModifiedBy=?
                            WHERE ID=?";
                DB::update($query, [
                    $request->txtFrmCustomerID,
                    $getAuth['UserID'],
                    $request->hdnFrmID
                ]);
                $return['message'] = "Data has been saved!";
                $return['callback'] = "doReloadTable()";

            }
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        return response()->json($return, 200);
    }

    public function doUpload(Request $request)
    {
        ini_set('memory_limit', '-1');
        $return = array('status'=>false,'message'=>"",'data'=>null,'callback'=>"");
        $getAuth = $this->validateAuth($request->_s);
        if ($getAuth['status'] && $getAuth['AccountType']==1) {
            if ($request->file('inpFile')->isValid()) {
                $tempFile = 'temp-'.time().'.'.$request->file('inpFile')->getClientOriginalExtension();
                $request->file('inpFile')->move('uploaded', $tempFile);
                $arrData = Excel::toArray(new GeneralImport(), 'uploaded/'.$tempFile);
                if (!$arrData) {
                    $return['message'] = 'File is empty';
                } else {
                    if ($arrData[0][0][0]!="ITEM ID" &&
                        $arrData[0][0][1]!="SKU *" &&
                        $arrData[0][0][2]!="PRODUCT NAME *" &&
                        $arrData[0][0][3]!="ALIAS1" &&
                        $arrData[0][0][4]!="ALIAS2" &&
                        $arrData[0][0][5]!="MARKET NAME" &&
                        $arrData[0][0][6]!="COLOR" &&
                        $arrData[0][0][7]!="CAPACITY" &&
                        $arrData[0][0][8]!="CATEGORY ID *" &&
                        $arrData[0][0][10]!="FOCUS PRODUCT *" &&
                        $arrData[0][0][12]!="DESCRIPTION" &&
                        $arrData[0][0][13]!="PRICE (RRP) *" &&
                        $arrData[0][0][14]!="PRICE (RRP) AFP *" &&
                        $arrData[0][0][15]!="PRICE (RRP) FTZ *" &&
                        $arrData[0][0][18]!="STATUS *") {
                        $return['message'] = 'Please use the correct file template';
                    } else {
                        if (count($arrData[0])==1) {
                            $return['message'] = 'File is empty';
                        } else {
                            $errMessage = "";
                            $tempData = [];
                            $exist_SKU = array();
                            for ($i=1; $i < count($arrData[0]); $i++) {
                                $ID = strtoupper(str_replace(' ','',$arrData[0][$i][0]));
                                $SKU = strtoupper(str_replace(' ','',$arrData[0][$i][1]));
                                $Name = strtoupper(str_replace("'",'`',$arrData[0][$i][2]));
                                $Alias1 = strtoupper(str_replace("'",'`',$arrData[0][$i][3]));
                                $Alias2 = strtoupper(str_replace("'",'`',$arrData[0][$i][4]));
                                $MarketName = strtoupper(str_replace("'",'`',$arrData[0][$i][5]));
                                $Color = $arrData[0][$i][6];
                                $Capacity = strtoupper(str_replace("'",'`',$arrData[0][$i][7]));
                                $CategoryCode = $arrData[0][$i][8];
                                $IsFocus = $arrData[0][$i][10];
                                $Description = strtoupper(str_replace("'",'`',$arrData[0][$i][12]));
                                $Price = $arrData[0][$i][13];
                                $PriceAFP = $arrData[0][$i][14];
                                $PriceFTZ = $arrData[0][$i][15];
                                $Status = $arrData[0][$i][18];
                                if (($ID!="" && $ID!=null) ||
                                    ($SKU!="" && $SKU!=null) ||
                                    ($Name!="" && $Name!=null) ||
                                    ($Alias1!="" && $Alias1!=null) ||
                                    ($Alias2!="" && $Alias2!=null) ||
                                    ($MarketName!="" && $MarketName!=null) ||
                                    ($Color!="" && $Color!=null) ||
                                    ($Capacity!="" && $Capacity!=null) ||
                                    ($CategoryCode!="" && $CategoryCode!=null) ||
                                    ($IsFocus!="" && $IsFocus!=null) ||
                                    ($Description!="" && $Description!=null) ||
                                    ($Price!="" && $Price!=null) ||
                                    ($PriceAFP!="" && $PriceAFP!=null) ||
                                    ($PriceFTZ!="" && $PriceFTZ!=null) ||
                                    ($Status!="" && $Status!=null))
                                {
                                    $message = "";
                                    $task = "";
                                    if ($ID==""||$ID==null) {
                                        $task = "insert";
                                        if ($SKU==""||$SKU==null) {
                                            if ($message!="") $message.= "<br /><br />";
                                            $message.= "SKU is required";
                                        } else {
                                            $query = "SELECT ID FROM ms_product WHERE UPPER(SKU)=UPPER(?)";
                                            $isExist = DB::select($query,[$SKU]);
                                            if ($isExist) {
                                                if ($message!="") $message.= "<br /><br />";
                                                $message.= "SKU already registered";
                                            }
                                            if (strlen($SKU)>50) {
                                                if ($message!="") $message.= "<br /><br />";
                                                $message.= "SKU max character allowed is 50";
                                            } else {
                                                if (isset($exist_SKU[$SKU])) {
                                                    if ($message!="") $message.= "<br /><br />";
                                                    $message.= "SKU already found in row ".$exist_SKU[$SKU];
                                                } else {
                                                    $exist_SKU[$SKU] = ($i+1);
                                                }
                                            }
                                        }
                                    } else {
                                        $task = "update";
                                        if ($SKU==""||$SKU==null) {
                                            if ($message!="") $message.= "<br /><br />";
                                            $message.= "SKU is required";
                                        } else {
                                            $query = "SELECT ID FROM ms_product
                                                        WHERE UPPER(ID)=UPPER(?) AND UPPER(SKU)=UPPER(?)";
                                            $isExist = DB::select($query,[$ID,$SKU]);
                                            if (!$isExist) {
                                                if ($message!="") $message.= "<br /><br />";
                                                $message.= "PRODUCT ID and SKU not found";
                                            }
                                        }
                                    }
                                    if ($Name==""||$Name==null) {
                                        if ($message!="") $message.= "<br /><br />";
                                        $message.= "PRODUCT NAME is required";
                                    } else {
                                        if (strlen($Name)>100) {
                                            if ($message!="") $message.= "<br /><br />";
                                            $message.= "PRODUCT NAME max character allowed is 100";
                                        }
                                    }
                                    if (strlen($Description)>250) {
                                        if ($message!="") $message.= "<br /><br />";
                                        $message.= "Description max character allowed is 250";
                                    }
                                    if ($Price==""||$Price==null) {
                                        if ($message!="") $message.= "<br /><br />";
                                        $message.= "PRICE (RRP) is required";
                                    }
                                    if ($PriceAFP==""||$PriceAFP==null) {
                                        if ($message!="") $message.= "<br /><br />";
                                        $message.= "PRICE (RRP) AFP is required";
                                    }
                                    if ($PriceFTZ==""||$PriceFTZ==null) {
                                        if ($message!="") $message.= "<br /><br />";
                                        $message.= "PRICE (RRP) FTZ is required";
                                    }
                                    if ($CategoryCode==""||$CategoryCode==null) {
                                        if ($message!="") $message.= "<br /><br />";
                                        $message.= "CATEGORY ID is required";
                                    } else {
                                        $CategoryID = "";
                                        $query = "SELECT ID FROM ms_references
                                                    WHERE Type='ProductCategoryList' AND UPPER(Field1)=UPPER(?)";
                                        $isExist = DB::select($query,[$CategoryCode]);
                                        if (!$isExist) {
                                            if ($message!="") $message.= "<br /><br />";
                                            $message.= "CATEGORY ID not found";
                                        }
                                        else $CategoryID = $isExist[0]->ID;
                                    }
                                    if ($IsFocus==""||$IsFocus==null) {
                                        if ($message!="") $message.= "<br /><br />";
                                        $message.= "FOCUS PRODUCT is required";
                                    } else {
                                        if ($IsFocus!="YES" && $IsFocus!="NO") {
                                            if ($message!="") $message.= "<br /><br />";
                                            $message.= "FOCUS PRODUCT only accept values either (YES / NO)";
                                        }
                                    }
                                    if (strlen($Alias1)>50) {
                                        if ($message!="") $message.= "<br /><br />";
                                        $message.= "ALIAS1 max character allowed is 50";
                                    }
                                    if (strlen($Alias2)>50) {
                                        if ($message!="") $message.= "<br /><br />";
                                        $message.= "ALIAS2 max character allowed is 50";
                                    }
                                    if (strlen($MarketName)>100) {
                                        if ($message!="") $message.= "<br /><br />";
                                        $message.= "MARKET NAME max character allowed is 100";
                                    }
                                    if (strlen($Color)>50) {
                                        if ($message!="") $message.= "<br /><br />";
                                        $message.= "COLOR max character allowed is 50";
                                    }
                                    if (strlen($Capacity)>50) {
                                        if ($message!="") $message.= "<br /><br />";
                                        $message.= "CAPACITY max character allowed is 50";
                                    }
                                    if ($Status==""||$Status==null) {
                                        if ($message!="") $message.= "<br /><br />";
                                        $message.= "STATUS is required";
                                    } else {
                                        if ($Status!="ACTIVE" && $Status!="INACTIVE") {
                                            if ($message!="") $message.= "<br /><br />";
                                            $message.= "STATUS only accept values either (ACTIVE / INACTIVE)";
                                        }
                                    }
                                    if ($message!="") {
                                        $errData = array(
                                            ($i+1),
                                            $message,
                                            $ID==null ? "" : $ID,
                                            $SKU==null ? "" : $SKU,
                                            $Name==null ? "" : $Name,
                                            $Description==null ? "" : $Description,
                                            $Price==null ? "" : $Price,
                                            $PriceAFP==null ? "" : $PriceAFP,
                                            $PriceFTZ==null ? "" : $PriceFTZ,
                                            $CategoryCode==null ? "" : $CategoryCode,
                                            $IsFocus==null ? "" : $IsFocus,
                                            $Alias1==null ? "" : $Alias1,
                                            $Alias2==null ? "" : $Alias2,
                                            $MarketName==null ? "" : $MarketName,
                                            $Color==null ? "" : $Color,
                                            $Capacity==null ? "" : $Capacity,
                                            $Status==null ? "" : $Status
                                        );
                                        array_push($tempData,$errData);
                                    }
                                    $errMessage .= $message;
                                }
                            }
                            if ($errMessage!="") {
                                $errData = array(
                                    'header' => array(
                                        'Row',
                                        'Error Description',
                                        'PRODUCT ID',
                                        'SKU *',
                                        'PRODUCT NAME *',
                                        'DESCRIPTION *',
                                        'PRICE (RRP) *',
                                        'PRICE (RRP) AFP *',
                                        'PRICE (RRP) FTZ *',
                                        'CATEGORY ID *',
                                        'FOCUS PRODUCT *',
                                        'ALIAS1',
                                        'ALIAS2',
                                        'MARKET NAME',
                                        'COLOR',
                                        'CAPACITY',
                                        'STATUS *'
                                    ),
                                    'data' => $tempData
                                );
                                $return['data'] = $errData;
                                $return['callback'] = "uploadError(e.data)";
                            } else {
                                for ($i=1; $i < count($arrData[0]); $i++) {
                                    $ID = strtoupper(str_replace(' ','',$arrData[0][$i][0]));
                                    $SKU = strtoupper(str_replace(' ','',$arrData[0][$i][1]));
                                    $Name = $arrData[0][$i][2];
                                    $Alias1 = $arrData[0][$i][3];
                                    $Alias2 = $arrData[0][$i][4];
                                    $MarketName = $arrData[0][$i][5];
                                    $Color = $arrData[0][$i][6];
                                    $Capacity = $arrData[0][$i][7];
                                    $CategoryCode = $arrData[0][$i][8];
                                    $IsFocus = $arrData[0][$i][10];
                                    $Description = $arrData[0][$i][12];
                                    $Price = $arrData[0][$i][13];
                                    $PriceAFP = $arrData[0][$i][14];
                                    $PriceFTZ = $arrData[0][$i][15];
                                    $Status = $arrData[0][$i][18];
                                    if (($ID!="" && $ID!=null) ||
                                        ($SKU!="" && $SKU!=null) ||
                                        ($Name!="" && $Name!=null) ||
                                        ($Alias1!="" && $Alias1!=null) ||
                                        ($Alias2!="" && $Alias2!=null) ||
                                        ($MarketName!="" && $MarketName!=null) ||
                                        ($Color!="" && $Color!=null) ||
                                        ($Capacity!="" && $Capacity!=null) ||
                                        ($CategoryCode!="" && $CategoryCode!=null) ||
                                        ($IsFocus!="" && $IsFocus!=null) ||
                                        ($Description!="" && $Description!=null) ||
                                        ($Price!="" && $Price!=null) ||
                                        ($PriceAFP!="" && $PriceAFP!=null) ||
                                        ($PriceFTZ!="" && $PriceFTZ!=null) ||
                                        ($Status!="" && $Status!=null))
                                    {
                                        $query = "SELECT ID FROM ms_references
                                                    WHERE Type='ProductCategoryList' AND UPPER(Field1)=UPPER(?)";
                                        $isExist = DB::select($query,[$CategoryCode]);
                                        $CategoryID = $isExist[0]->ID;

                                        $SellIn = 0;
                                        $SellThru = 0;
                                        $SellInAFP = 0;
                                        $SellThruAFP = 0;
                                        $SellInFTZ = 0;
                                        $SellThruFTZ = 0;

                                        //Reguler Price
                                        $SellIn = $this->getPrice($CategoryID, $Price, 'SELLIN');
                                        $SellThru = ceil($this->getPrice($category, $Price, 'SELLTHRU') / 1000) * 1000;

                                        //AFP Price
                                        $SellInAFP = $this->getPrice($CategoryID, $PriceAFP, 'SELLINAFP');
                                        $SellThruAFP = ceil($this->getPrice($category, $PriceAFP, 'SELLTHRUAFP') / 1000) * 1000;

                                        //FTZ Price
                                        $SellInFTZ = $this->getPrice($CategoryID, $PriceFTZ, 'SELLINFTZ');
                                        $SellThruFTZ = ceil($this->getPrice($category, $PriceFTZ, 'SELLTHRUFTZ') / 1000) * 1000;

                                        if ($ID==""||$ID==null) {
                                            $task = "insert";
                                            $query = "INSERT INTO ms_product
                                                        (ID, SKU, Name, Description, CategoryID, UnitPrice, InPrice, ThruPrice, UnitPriceAFP, InPriceAFP, ThruPriceAFP, UnitPriceFTZ, InPriceFTZ, ThruPriceFTZ, IsFocus, Alias1, Alias2, MarketName, Color, Capacity, Status, CreatedDate, CreatedBy, ModifiedDate, ModifiedBy)
                                                        VALUES(UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, NULL, NULL)";
                                            DB::insert($query, [
                                                $SKU,
                                                $Name,
                                                $Description,
                                                $CategoryID,
                                                $Price,
                                                $SellIn,
                                                $SellThru,
                                                $PriceAFP,
                                                $SellInAFP,
                                                $SellThruAFP,
                                                $PriceFTZ,
                                                $SellInFTZ,
                                                $SellThruFTZ,
                                                $IsFocus=="YES" ? 1 : 0,
                                                $Alias1,
                                                $Alias2,
                                                $MarketName,
                                                $Color,
                                                $Capacity,
                                                $Status=="ACTIVE" ? 1 : 0,
                                                $getAuth['UserID']
                                            ]);
                                        } else {
                                            $task = "update";
                                            $query = "UPDATE ms_product
                                                        SET Name=?,
                                                            Description=?,
                                                            CategoryID=?,
                                                            UnitPrice=?,
                                                            InPrice=?,
                                                            ThruPrice=?,
                                                            UnitPriceAFP=?,
                                                            InPriceAFP=?,
                                                            ThruPriceAFP=?,
                                                            UnitPriceFTZ=?,
                                                            InPriceFTZ=?,
                                                            ThruPriceFTZ=?,
                                                            IsFocus=?,
                                                            Alias1=?,
                                                            Alias2=?,
                                                            MarketName=?,
                                                            Color=?,
                                                            Capacity=?,
                                                            Status=?,
                                                            ModifiedDate=NOW(),
                                                            ModifiedBy=?
                                                        WHERE ID=?";
                                            DB::update($query, [
                                                $Name,
                                                $Description,
                                                $CategoryID,
                                                $Price,
                                                $SellIn,
                                                $SellThru,
                                                $PriceAFP,
                                                $SellInAFP,
                                                $SellThruAFP,
                                                $PriceFTZ,
                                                $SellInFTZ,
                                                $SellThruFTZ,
                                                $IsFocus=="YES" ? 1 : 0,
                                                $Alias1,
                                                $Alias2,
                                                $MarketName,
                                                $Color,
                                                $Capacity,
                                                $Status=="ACTIVE" ? 1 : 0,
                                                $getAuth['UserID'],
                                                $ID
                                            ]);
                                        }
                                    }
                                }
                                $return['status'] = true;
                                $return['message'] = 'Data uploaded successfully';
                                $return['data'] = $arrData;
                                $return['callback'] = "doReloadTable()";
                            }
                        }
                    }
                }
                unlink('uploaded/'.$tempFile);
            }
        } else $return = array('message'=>"Not Authorized");
        return response()->json($return, 200);
    }

    public function doDelete(Request $request)
    {
        $return = array('status'=>true,'message'=>"",'data'=>null,'callback'=>"");
        $getAuth = $this->validateAuth($request->_s);
        if ($getAuth['status']) {
            $query = "DELETE FROM tr_transaction WHERE ID=?";
            DB::delete($query, [$request->_i]);
            $return['message'] = "Data has been removed!";
            $return['callback'] = "doReloadTable()";
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        return response()->json($return, 200);
    }

    public function getItems(Request $request)
    {
        $return = array('status'=>true,'message'=>"",'data'=>array(),'callback'=>"");
        $getAuth = $this->validateAuth($request->_s);
        if ($getAuth['status']) {
            if ($request->_i) {
                $query = "SELECT tr.ID,
                            p.Name ProductName,
                            tr.Price,
                            tr.Amount,
                            (tr.Price * tr.Amount) Total,
                            tr.Status
                        FROM tr_transaction_detail tr
                            LEFT JOIN ms_product p ON p.ID=tr.ProductID
                        WHERE tr.TransactionID=?
                        GROUP BY p.Name,
                            tr.Price,
                            tr.Amount,
                            tr.Status,
                            tr.ID
                            ";
                $data = DB::select($query,[$request->_i]);
                if ($data) $return['data'] = $data;
            }
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        return response()->json($return, 200);
    }

    public function doSaveItem(Request $request)
    {
        $return = array('status'=>true,'message'=>"",'data'=>null,'callback'=>"");
        $getAuth = $this->validateAuth($request->_s);
        if ($getAuth['status']) {
            if ($request->hdnFrmAction=="add") {
                if (intval($request->txtFrmQty) > 0){
                    $query = "SELECT ID, Name, Price, OnStock FROM ms_product WHERE ID = ?";
                    $refData = DB::select($query, [$request->hdnFrmProductID]);
                    if ($refData[0]->OnStock >= $request->txtFrmQty) {
                        $query = "INSERT INTO tr_transaction_detail
                                    (ID, TransactionID, Price, Amount, Status, ProductID)
                                    VALUES(UUID(), ?, ?, ?, ?, ?)";
                        DB::insert($query, [
                            $request->txtFrmTransactionID,
                            $refData[0]->Price,
                            $request->txtFrmQty,
                            '1',
                            $request->hdnFrmProductID,
                            $getAuth['UserID']
                        ]);

                        //Update Stock
                        $newStock = $refData[0]->OnStock - $request->txtFrmQty;
                        $query = "UPDATE ms_product SET OnStock=?,
                                        ModifiedDate=NOW(),
                                        ModifiedBy=?
                                    WHERE ID=?";
                        DB::update($query, [
                            $newStock,
                            $getAuth['UserID'],
                            $request->hdnFrmProductID
                        ]);

                        $return['message'] = "Data has been saved!";
                        $return['callback'] = "loadPage('transaction/detail/index','onDetailForm(`".$request->txtFrmTransactionID."`,true)')";
                    } else $return = array('status'=>false,'message'=>"Stock < Amount");
                } else $return = array('status'=>false,'message'=>"Amount must be greater than 0");
            }
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        return response()->json($return, 200);
    }

    public function doDeleteItem(Request $request)
    {
        $return = array('status'=>true,'message'=>"",'data'=>null,'callback'=>"");
        $getAuth = $this->validateAuth($request->_s);
        if ($getAuth['status']) {
            $query = "SELECT ID, ProductID, Amount FROM tr_transaction_detail WHERE ID = ?";
            $refData = DB::select($query, [$request->_i]);

            $query = "SELECT ID, Name, Price, OnStock FROM ms_product WHERE ID = ?";
            $ProductData = DB::select($query, [$refData[0]->ProductID]);

             //Update Stock
            $newStock = $ProductData[0]->OnStock + $refData[0]->Amount;
            $query = "UPDATE ms_product SET OnStock=?,
                            ModifiedDate=NOW(),
                            ModifiedBy=?
                        WHERE ID=?";
            DB::update($query, [
                $newStock,
                $getAuth['UserID'],
                $refData[0]->ProductID
            ]);

            $query = "DELETE FROM tr_transaction_detail WHERE ID=?";
            DB::delete($query, [$request->_i]);

            $return['message'] = "Data has been removed!";
            $return['callback'] = "loadPage('transaction/detail/index','onDetailForm(`".$request->transactionID."`,true)')";
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        return response()->json($return, 200);
    }

    public function doLookup(Request $request)
    {
        $return = array('status'=>true,'message'=>"",'data'=>null,'callback'=>"");
        $getAuth = $this->validateAuth($request->_s);
        if ($getAuth['status']) {
            $keywords = $this->sanitizeString($request->_i);
            $query = "SELECT ID,
                    Name,
                    LastPurchase
                    FROM ms_customer
                    WHERE Status=1
                    AND (Name LIKE '%$keywords%')
                    ORDER BY CreatedDate ASC";
            $data = DB::select($query);
            if ($data) $return['data'] = $data;
            if ($request->_cb) $return['callback'] = $request->_cb."(e.data)";
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        return response()->json($return, 200);
    }
}
