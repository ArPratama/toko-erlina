<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    private function randomString($length) {
		$characters = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
	private function strEncrypt($salt,$string) {
		$return = array('result'=>"",'iv'=>NULL,'tag'=>NULL);
        $cipher = "aes-128-gcm";
        if (in_array($cipher, openssl_get_cipher_methods())) {
            $ivlen = openssl_cipher_iv_length($cipher);
            $iv = openssl_random_pseudo_bytes($ivlen);
            $return['result'] = openssl_encrypt($string, $cipher, $salt, $options=0, $iv, $tag);
            $return['iv'] = $iv;
            $return['tag'] = $tag;
        }
        return $return;
	}
    private function strDecrypt($salt,$iv,$tag,$encrypted) {
		$return = "";
        $cipher = "aes-128-gcm";
        if (in_array($cipher, openssl_get_cipher_methods())) {
            $return = openssl_decrypt($encrypted, $cipher, $salt, $options=0, $iv, $tag);
        }
        return $return;
	}

    public function doLogin(Request $request)
    {
        $return = array('status'=>false,'message'=>"",'data'=>null,'callback'=>"");
        $return['message'] = "Invalid Username or Password";
        $query = "SELECT ID,FullName,RoleID,Status,Password,Salt,IVssl,Tagssl
                    FROM MS_USER
                    WHERE UPPER(UserName) = UPPER(?)";
        $data = DB::select($query,[$request->txtEmail]);
        if ($data) {
            $data = $data[0];
            if ($data->Status==1) {
                $decrypted = $this->strDecrypt(base64_decode($data->Salt),base64_decode($data->IVssl),base64_decode($data->Tagssl),base64_decode($data->Password));
                if ($decrypted == $request->txtPassword) {
                    $SessionID = base64_encode($this->randomString(64).base64_encode(md5($data->ID).time()));
                    $IP = getenv('REMOTE_ADDR');
                    $query = "INSERT INTO TR_SESSION (ID, UserID, Token, LoginDate, LogoutDate, IPAddress, LastActive)
                                VALUES (UUID(), ?, ?, NOW(), NULL, ?, NOW())";
                    DB::insert($query,[$data->ID,$SessionID,$IP]);
                    $return = array(
                        'status' => true,
                        'message' => "",
                        'data' => array(
                            'Token' => $SessionID,
                            'RememberLogin' => $request->chkRemember ? true : false
                        ),
                        'callback' => "app.auth.loginHandler(e.data)"
                    );
                }
            } else {
                $return['message'] = "User is inactive";
            }
        }
        return response()->json($return, 200);
    }

    public function doAuth(Request $request)
    {
        $return = array('status'=>false,'message'=>"",'data'=>null,'callback'=>"");
        $query = "SELECT u.ID,u.FullName,u.AccountType
                FROM TR_SESSION s
                    JOIN MS_USER u ON u.ID = s.UserID
                    JOIN MS_ROLE r ON r.ID = u.RoleID
                WHERE s.Token = ?
                    AND s.LogoutDate IS NULL";
        $data = DB::select($query,[$request->_s]);
        if ($data) {
            $userData = $data[0];
            $query = "SELECT m.ID,m.Name,m.URL,m.Icon,m.ParentID
                FROM TR_SESSION s
                    JOIN MS_USER u ON u.ID = s.UserID
                    JOIN MS_ROLE_ACCESS r ON r.RoleID = u.RoleID
                    JOIN MS_MENU m ON m.ID = r.MenuID
                WHERE s.Token = ?
                    AND s.LogoutDate IS NULL
                ORDER BY m.ParentID ASC, m.Sequence ASC";
            $accessMenu = DB::select($query,[$request->_s]);
            $arrData = array(
                'userData' => $data[0],
                'accessMenu' => $accessMenu
            );
            $return = array(
                'status' => true,
                'message' => "",
                'data' => $arrData,
                'callback' => "app.init(e.data)"
            );
        } else $return = array('status'=>false,'message'=>"Not Authorized",'callback'=>"app.auth.notAuthorizedHandler(e.message)");
        return response()->json($return, 200);
    }

    public function doLogout(Request $request)
    {
        $return = array('status'=>false,'message'=>"",'data'=>null,'callback'=>"");
        $query = "UPDATE TR_SESSION
                    SET LogoutDate = NOW()
                    WHERE Token = ?";
        DB::insert($query,[$request->Token]);
        $return = array(
            'status' => true,
            'message' => "You have been logged out",
            'callback' => "app.auth.clearSession()"
        );
        return response()->json($return, 200);
    }

    public function doReset(Request $request)
    {
        $return = array('status'=>false,'message'=>"",'data'=>null,'callback'=>"");
        $return['message'] = "User not found or inactive";
        $query = "SELECT ID,FullName,RoleID,Password,Salt,IVssl,Tagssl
                    FROM MS_USER
                    WHERE UPPER(UserName) = UPPER(?)
                            AND Status = 1";
        $data = DB::select($query,[$request->txtEmail]);
        if ($data) {
            $return = array(
                'status' => true,
                'message' => "Please check your email for further instruction",
                'callback' => ""
            );
        }
        return response()->json($return, 200);
    }

}
