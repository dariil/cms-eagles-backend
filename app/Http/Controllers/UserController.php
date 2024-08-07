<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Club;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Validator;
use Auth;

use Haruncpi\LaravelIdGenerator\IdGenerator;

class UserController extends Controller
{
    //

    //LOGIN
    function login(Request $req){
        $user = User::where('email', $req->email)->first();
        if(!$user || !Hash::check($req->password, $user->password)){
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Incorrect email or password.'
                ]
            ]);
        } else{
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'User logged in successfully.'
                ],
                'response' => $user
            ];
            return response()->json($response);
        }
    }

    //ADD REQUESTS
    function addUser(Request $req): JsonResponse {
        $user = new User;
        $user_id_config = ['table'=>'tbl_users', 'field'=>'user_id','length'=>10,'prefix'=>'MBMR-'];
        $user->user_id = IdGenerator::generate($user_id_config);
        $user->club_id = $req->input('club_member');
        $user->first_name = $req->input('first_name');
        $user->middle_name = $req->input('middle_name');
        $user->last_name = $req->input('last_name');
        $user->position = $req->input('position');
        $user->number = $req->input('number');
        $user->email = $req->input('email');
        $user->password = Hash::make($req->input('password'));
        $user->access_level = $req->input('access_level');
        
        if ($user->save()) {
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Record has been created successfully.'
                ],
                'response' => $user
            ];
            return response()->json($response);
        } else {
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Failed to create record.'
                ]
            ]);
        }
    }

    //GET REQUESTS
    function getUsers($access_level){
        $users = User::where('access_level', $access_level)
                 ->with('club:club_id,club_name')
                 ->get()
                 ->map(function ($user) {
                     return [
                         'user_id' => $user->user_id,
                         'club_id' => $user->club_id,
                         'first_name' => $user->first_name,
                         'middle_name' => $user->middle_name,
                         'last_name' => $user->last_name,
                         'position' => $user->position,
                         'number' => $user->number,
                         'email' => $user->email,
                         'access_level' => $user->access_level,
                         'date_created' => $user->date_created,
                         'club_id' => $user->club->club_id,
                         'club_name' => $user->club->club_name,
                     ];
                 });

        return response()->json($users);
        // return User::all();
    }

    function getAdmin($access_level){
        $users = User::where('access_level', $access_level)->get();
        return response()->json($users);
    }

    function getUsersInClub($club_id, $access_level){
        $users = User::where('club_id', $club_id)->where('access_level', $access_level)
            ->get()
            ->map(function ($user){
                return [
                    'user_id' => $user->user_id,
                    'club_id' => $user->club_id,
                    'first_name' => $user->first_name,
                    'middle_name' => $user->middle_name,
                    'last_name' => $user->last_name,
                    'position' => $user->position,
                    'number' => $user->number,
                    'email' => $user->email,
                    'access_level' => $user->access_level,
                    'date_created' => $user->date_created,
                    // 'club_id' => $user->club->club_id,
                    'club_name' => $user->club->club_name,
                ];
            });
        return response()->json($users);
    }

    function getAdminInClub($club_id, $access_level){
        $users = User::where('club_id', $club_id)->where('access_level', $access_level)->get();
        return response()->json($users);
    }

    function getOneUser($user_id){
        $users = User::where('user_id', $user_id)
            ->with('club:club_id,club_name')
            ->get()
            ->map(function ($user){
                return [
                    'user_id' => $user->user_id,
                    'club_id' => $user->club_id,
                    'first_name' => $user->first_name,
                    'middle_name' => $user->middle_name,
                    'last_name' => $user->last_name,
                    'position' => $user->position,
                    'number' => $user->number,
                    'email' => $user->email,
                    'access_level' => $user->access_level,
                    'date_created' => $user->date_created,
                    'club_id' => $user->club->club_id,
                    'club_name' => $user->club->club_name,
                ];
            });
        return response()->json($users);
    }

    //UPDATE REQUESTS
    function updateUser($user_id, Request $req){
        $user = User::find($user_id);
        $user->first_name = $req->input('first_name');
        $user->middle_name = $req->input('middle_name');
        $user->last_name = $req->input('last_name');
        $user->position = $req->input('position');
        $user->number = $req->input('number');
        $user->email = $req->input('email');
        $user->access_level = $req->input('access_level');
        $user->save();
        if($user->save()){
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'User has been updated successfully.',
                ],
                'response' => $user
            ];
            return response()->json($response);
        } else{
            return response()->json([
                'messages' => [
                    'status' => '0',
                    'message' => 'Failed to update user.',
                ]
            ]);
        }
    }

    //DELETE REQUESTS
    function deleteUser($user_id){
        $result = User::where('user_id', $user_id)->delete();
        if($result){
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'User has been deleted permanently.'
                ],
                'response' => $result
            ];
            return response()->json($response);
        } else{
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Failed to delete product.'
                ]
            ]);
        }
        // return $id;
    }

    //EXPERIMENTAL
    // public function userLogin(Request $request)
    // {
    //     $input = $request->all();
    //     $vallidation = Validator::make($input,[
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     if($vallidation->fails()){
    //         return response()->json(['error' => $vallidation->errors()],422);
    //     }


    //     if (Auth::attempt(['email' => $input['email'],'password' => $input['password']])) {
    //         $user  = Auth::user();
    //         // dd($user);

    //         $token = $user->createToken('MyApp')->accessToken;

    //         return response()->json(['token' => $token]);
    //     }
    // }

    function userLogin(Request $req) {
        $user = User::where('email', $req->email)->first();
        if (!$user || !Hash::check($req->password, $user->password)) {
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Incorrect email or password.'
                ]
            ]);
        } else {
            $token = $user->createToken('MyApp')->accessToken;
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'User logged in successfully.'
                ],
                'response' => $user,
                'token' => $token
            ];
            return response()->json($response);
        }
    }
}
