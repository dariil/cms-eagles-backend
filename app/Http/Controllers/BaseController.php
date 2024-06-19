<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    //
    public function sendResponse($result, $message){
        $reponse = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($reponse, 200);
    }

    public function sendError($result, $errorMessage=[],$code = 404){
        $reponse = [
            'success' => false,
            'message' => $errorMessage,
        ];

        if(!empty($errorMessage)){
            $response['data'] = $errorMessage;
        }

        return response()->json($reponse, $code);
    }
}
