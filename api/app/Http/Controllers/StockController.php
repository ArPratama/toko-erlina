<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Exports\GeneralExport;
use App\Imports\GeneralImport;
use Maatwebsite\Excel\Facades\Excel;

class StockController extends Controller
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
            $mainQuery = "SELECT st.ID, st.ProductID, p.Name ProductName, st.Amount, st.Status, st.Source, st.CreatedDate, st.CreatedBy, st.IncomingDate
                            FROM ms_stock st
                            LEFT JOIN ms_product p ON p.ID = st.ProductID
                            WHERE {definedFilter}
                            ORDER BY st.CreatedDate DESC
                            {Limit}";
            $definedFilter = "1=1";
            if ($request->_i) {
                $definedFilter = "st.ID=?";
                $mainQuery = str_replace("{Limit}",'',$mainQuery);
                $query = str_replace("{definedFilter}",$definedFilter,$mainQuery);
                $data = DB::select($query,[$request->_i]);
                if ($data) {
                    $return['data'] = $data[0];
                    $return['callback'] = "onCompleteFetch(e.data)";
                }
            } else {
                if (!$request->_export) {
                    if ($request->columns[0]['search']['value'] != '') $definedFilter.= " AND ID LIKE '%".$this->sanitizeString($request->columns[0]['search']['value'])."%' ";
                    if ($request->columns[1]['search']['value'] != '') $definedFilter.= " AND p.Name LIKE '%".$this->sanitizeString($request->columns[1]['search']['value'])."%' ";

                    $total = 0;
                    $mainQueryTotal = "SELECT COUNT(ID) Total
                                        FROM ms_stock
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
            $filename = 'Stock_Management';
            $arrData = [];
            $arrHeader = array(
                "ITEM ID",
                "PRODUCT NAME",
                "AMOUNT",
                "STATUS",
                "SOURCE",
                "INCOMING DATE",
                "CREATED DATE",
                "CREATED BY"
            );
            array_push($arrData,$arrHeader);
            foreach ($return['data'] as $key => $value) {
                $rows = array(
                    $value->ID,
                    $value->ProductName,
                    $value->Amount,
                    $value->Status==1 ? "DISPLAY" : "ON STORAGE",
                    $value->Source,
                    $value->IncomingDate,
                    $value->CreatedDate,
                    $value->CreatedBy
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
                $query = "INSERT INTO ms_stock
                            (ID, ProductID, Amount, Status, Source, CreatedDate, CreatedBy, ModifiedDate, ModifiedBy, IncomingDate)
                            VALUES(UUID(), ?, ?, ?, ?, NOW(), ?, NULL, NULL, ?)";
                DB::insert($query, [
                    $request->txtFrmProductID,
                    $request->txtFrmAmount,
                    $request->radFrmStatus,
                    $request->txtFrmSource,
                    $getAuth['UserID'],
                    $request->txtFrmIncomingDate,
                ]);

                if($request->radFrmStatus == "1"){
                    //Update Stock on Product
                    $query = "SELECT ID,
                            Name,
                            OnStock
                            FROM ms_product
                            WHERE ID = ?";
                    $dataProduct = DB::select($query, [$request->txtFrmProductID]);

                    $onStockNew = $dataProduct[0]->OnStock + $request->txtFrmAmount;

                    $query = "UPDATE ms_product SET OnStock=?, ModifiedDate=NOW(), ModifiedBy=? WHERE ID = ?";
                    DB::update($query, [
                        $onStockNew,
                        $getAuth['UserID'],
                        $request->txtFrmProductID,
                    ]);
                }
                $return['message'] = "Data has been saved!";
                $return['callback'] = "doReloadTable()";
            }
            if ($request->hdnFrmAction=="edit") {
                $query = "UPDATE ms_stock
                            SET ProductID=?,
                                Amount=?,
                                Source=?,
                                Status=?,
                                ModifiedDate=NOW(),
                                ModifiedBy=?
                                IncomingDate=?
                            WHERE ID=?";
                DB::update($query, [
                    $request->txtFrmProductID,
                    $request->txtFrmAmount,
                    $request->txtFrmSource,
                    $request->radFrmStatus,
                    $getAuth['UserID'],
                    $request->radFrmIncomingDate,
                    $request->hdnFrmID
                ]);
                $return['message'] = "Data has been saved!";
                $return['callback'] = "doReloadTable()";
            }

            if ($request->hdnFrmAction=="statusBulk") {
                $arrData = explode(",",$request->hdnFrmID);
                if (count($arrData)==0) {
                    $return = array('status'=>false,'message'=>"Please select item first");
                } else {
                    foreach ($arrData as $key => $value) {
                        //Update Stock on Product
                        $query = "SELECT ID,
                                Amount,
                                ProductID
                                FROM ms_stock
                                WHERE ID = ?";
                        $dataStock = DB::select($query, [$value]);

                        $query = "SELECT ID,
                                Name,
                                OnStock
                                FROM ms_product
                                WHERE ID = ?";
                        $dataProduct = DB::select($query, [$dataStock[0]->ProductID]);

                        if($request->selFrmStatus == "1"){
                            $onStockNew = $dataProduct[0]->OnStock + $dataStock[0]->Amount;

                            $query = "UPDATE ms_product SET OnStock=?, ModifiedDate=NOW(), ModifiedBy=? WHERE ID = ?";
                            DB::update($query, [
                                $onStockNew,
                                $getAuth['UserID'],
                                $dataProduct[0]->ID,
                            ]);
                        }

                        if($request->selFrmStatus == "2"){
                            if($dataStock[0]->Amount > $dataProduct[0]->OnStock){
                                $return = array('status'=>false,'message'=>"Data stock cannot be reduced again");
                                return response()->json($return, 200);
                            }
                            $onStockNew = $dataProduct[0]->OnStock - $dataStock[0]->Amount;

                            $query = "UPDATE ms_product SET OnStock=?, ModifiedDate=NOW(), ModifiedBy=? WHERE ID = ?";
                            DB::update($query, [
                                $onStockNew,
                                $getAuth['UserID'],
                                $dataProduct[0]->ID,
                            ]);
                        }
                        //

                        $query = "UPDATE ms_stock SET Status=?,ModifiedDate=NOW(),ModifiedBy=? WHERE ID=?";
                        DB::update($query, [
                            $request->selFrmStatus,
                            $getAuth['UserID'],
                            $value
                        ]);
                    }
                    $return['message'] = count($arrData)." data has been saved!";
                    $return['callback'] = "doReloadTable()";
                }
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
            $query = "DELETE FROM ms_product WHERE ID=?";
            DB::delete($query, [$request->_i]);
            $return['message'] = "Data has been removed!";
            $return['callback'] = "doReloadTable()";
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        return response()->json($return, 200);
    }

    public function doLookup(Request $request)
    {
        $return = array('status'=>true,'message'=>"",'data'=>null,'callback'=>"");
        $getAuth = $this->validateAuth($request->_s);
        if ($getAuth['status']) {
            if($request->ver == 'v2'){
                $keywords = $this->sanitizeString($request->_i);
                $query = "SELECT ID,
                        SKU,
                        Name,
                        Description,
                        UnitPrice,
                        InPrice,
                        ThruPrice,
                        UnitPriceAFP,
                        InPriceAFP,
                        ThruPriceAFP,
                        UnitPriceFTZ,
                        InPriceFTZ,
                        ThruPriceFTZ,
                        Color,
                        Capacity,
                        MarketName
                        FROM ms_product
                        WHERE Status=1
                        AND (SKU LIKE '%$keywords%' OR Name LIKE '%$keywords%' OR MarketName LIKE '%$keywords%' OR Color LIKE '%$keywords%' OR Capacity LIKE '%$keywords%')
                        ORDER BY SKU ASC";
                $data = DB::select($query);
                if ($data) $return['data'] = $data;
            }else{
                $query = "SELECT ID,
                        SKU,
                        Name,
                        Description,
                        UnitPrice,
                        InPrice,
                        ThruPrice,
                        UnitPriceAFP,
                        InPriceAFP,
                        ThruPriceAFP,
                        UnitPriceFTZ,
                        InPriceFTZ,
                        ThruPriceFTZ
                        FROM ms_product
                        WHERE Status=1
                        AND UPPER(SKU)=UPPER(?)";
                $data = DB::select($query,[$request->_i]);
                if ($data) $return['data'] = $data[0];
            }
            if ($request->_cb) $return['callback'] = $request->_cb."(e.data)";
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        return response()->json($return, 200);
    }
}
