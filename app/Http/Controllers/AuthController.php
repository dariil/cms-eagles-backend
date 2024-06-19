<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends BaseController
{
    //
    public function getToken(Request $req){
        $user = User::where('email', $req->email)->first();
        $credentials = request(['email', 'password']);
        $token = auth()->attempt($credentials);
        if (!$user || !Hash::check($req->password, $user->password)) {
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Incorrect email or password.'
                ]
            ]);
        } else {
            $tokenResponse = $this->respondWithToken($token);
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Token Acquired Successfully.'
                ],
                'token' => $tokenResponse
            ];
            return response()->json($response);
        }
    }

    protected function respondWithToken($token){
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL()*60,
        ]; 
    }

    public function index(){
        $data =[
            'id' => 1,
            'name' => "John Doe",
            "email" => "johndoe@gmail.com",
        ];

        return response()->json($data);
    }
}
