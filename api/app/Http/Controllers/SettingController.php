<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class SettingController extends Controller
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
                    FROM ms_user u
                        JOIN tr_session s ON s.UserID = u.ID
                    WHERE s.Token=?
                        AND s.LogoutDate IS NULL";
        $checkAuth = DB::select($query,[$Token]);
        if ($checkAuth) {
            $data = $checkAuth[0];
            $query = "UPDATE tr_session SET LastActive=GETDATE() WHERE Token=?";
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

    public function getAll(Request $request)
    {
        $return = array('status'=>true,'message'=>"",'data'=>array(),'callback'=>"");
        $getAuth = $this->validateAuth($request->_s);
        if ($getAuth['status']) {
            $mainQuery = "SELECT ID,Type,Field1,Field2,Field3,IsEditable
                            FROM ms_references
                            WHERE {definedFilter}
                            ORDER BY Type ASC, Field1 ASC, Field2 ASC";
            $definedFilter = "1=1";
            if ($request->_i) {
                $definedFilter = "ID=?";
                $query = str_replace("{definedFilter}",$definedFilter,$mainQuery);
                $data = DB::select($query,[$request->_i]);
                if ($data) {
                    $return['data'] = $data[0];
                    $return['callback'] = "onCompleteFetch(e.data)";
                }
            } else {
                $query = str_replace("{definedFilter}",$definedFilter,$mainQuery);
                $data = DB::select($query);
                if ($data) $return['data'] = $data;
            }
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        return response()->json($return, 200);
    }

    public function doSave(Request $request)
    {
        $return = array('status'=>true,'message'=>"",'data'=>null,'callback'=>"");
        $getAuth = $this->validateAuth($request->_s);
        if ($getAuth['status']) {
            if ($request->hdnFrmAction=="add") {
                $query = "SELECT ID FROM ms_references WHERE Type=? AND Field1=?";
                $isExist = DB::select($query, [$request->txtFrmType, $request->txtFrmField1]);
                if (!$isExist) {
                    $query = "INSERT INTO ms_references
                                    (ID, [Type], Field1, Field2, Field3, IsEditable, Status, CreatedDate, CreatedBy, ModifiedDate, ModifiedBy)
                                VALUES(NEWID(), ?, ?, ?, ?, 1, 1, GETDATE(), ?, NULL, NULL)";
                    DB::insert($query, [
                        $request->txtFrmType,
                        $request->txtFrmField1,
                        $request->txtFrmField2,
                        $request->txtFrmField3,
                        $getAuth['UserID']
                    ]);
                    $return['message'] = "Data has been saved!";
                    $return['callback'] = "doReloadTable()";
                } else {
                    $return['status'] = false;
                    $return['message'] = "Type and Field1 already registered";
                }
            }
            if ($request->hdnFrmAction=="edit") {
                $query = "SELECT ID FROM ms_references WHERE Type=? AND Field1=? AND ID!=?";
                $isExist = DB::select($query, [$request->txtFrmType, $request->txtFrmField1, $request->hdnFrmID]);
                if (!$isExist) {
                    $query = "UPDATE ms_references
                                SET Type=?,
                                    Field1=?,
                                    Field2=?,
                                    Field3=?,
                                    ModifiedDate=GETDATE(),
                                    ModifiedBy=?
                                WHERE ID=?";
                    DB::update($query, [
                        $request->txtFrmType,
                        $request->txtFrmField1,
                        $request->txtFrmField2,
                        $request->txtFrmField3,
                        $getAuth['UserID'],
                        $request->hdnFrmID
                    ]);
                    $return['message'] = "Data has been saved!";
                    $return['callback'] = "doReloadTable()";
                } else {
                    $return['status'] = false;
                    $return['message'] = "Type and Field1 already registered";
                }
            }
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        return response()->json($return, 200);
    }

    public function doDelete(Request $request)
    {
        $return = array('status'=>true,'message'=>"",'data'=>null,'callback'=>"");
        $getAuth = $this->validateAuth($request->_s);
        if ($getAuth['status']) {
            $query = "SELECT ID FROM ms_references WHERE ID=? AND IsEditable=1";
            $isExist = DB::select($query, [$request->_i]);
            if ($isExist) {
                $query = "DELETE FROM ms_references WHERE ID=? AND IsEditable=1";
                DB::delete($query, [$request->_i]);
                $return['message'] = "Data has been removed!";
                $return['callback'] = "doReloadTable()";
            } else $return = array('status'=>false,'message'=>"Not Authorized");
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        return response()->json($return, 200);
    }

}
