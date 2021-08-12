<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class GlobalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

	private function numberFormat($dec, $comma='.', $thousand=',') {
		return number_format($dec,0,$comma,$thousand);
	}
    private function validateAuth($Token) {
        $return = array('status'=>false,'UserID'=>"");
        $query = "SELECT u.ID, u.AccountType
                    FROM MS_USER u
                        JOIN TR_SESSION s ON s.UserID = u.ID
                    WHERE s.Token = ?
                        AND s.LogoutDate IS NULL";
        $checkAuth = DB::select($query,[$Token]);
        if ($checkAuth) {
            $data = $checkAuth[0];
            $return = array(
                'status' => true,
                'UserID' => $data->ID,
                'AccountType' => $data->AccountType
            );
        }
        return $return;
    }

    public function getMenu(Request $request)
    {
        $return = array('status'=>true,'message'=>"",'data'=>null,'callback'=>"");
        $getAuth = $this->validateAuth($request->_s);
        if ($getAuth['status']) {
            $query = "SELECT ID,Name FROM MS_MENU WHERE Status=1 ORDER BY ParentID, Sequence ASC";
            $return['data'] = DB::select($query);
            if ($request->_cb) $return['callback'] = $request->_cb."(e.data,'".$request->_p."')";
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        return response()->json($return, 200);
    }

    public function getRole(Request $request)
    {
        $return = array('status'=>true,'message'=>"",'data'=>null,'callback'=>"");
        $getAuth = $this->validateAuth($request->_s);
        if ($getAuth['status']) {
            $query = "SELECT ID,Name FROM MS_ROLE WHERE Status=1 AND ID!='SYSTEM' ORDER BY Name ASC";
            $return['data'] = DB::select($query);
            if ($request->_cb) $return['callback'] = $request->_cb."(e.data,'".$request->_p."')";
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        return response()->json($return, 200);
    }

    public function getMaster(Request $request)
    {
        $return = array('status'=>true,'message'=>"",'data'=>null,'callback'=>"");
        $getAuth = $this->validateAuth($request->_s);
        if ($getAuth['status']) {
            $query = "SELECT ID,CONCAT('[',Field1,'] ',Field2) Name FROM MS_REFERENCES WHERE Status=1 AND Type=? ORDER BY Field1 ASC";
            $return['data'] = DB::select($query,[$request->type]);
            if ($request->_cb) $return['callback'] = $request->_cb."(e.data,'".$request->_p."')";
        } else $return = array('status'=>false,'message'=>"Not Authorized");
        return response()->json($return, 200);
    }
}
