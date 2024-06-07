<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Club;

class ClubController extends Controller
{
    //
    function addClub(Request $req){
        $club = new Club;
        $club->club_code=$req->input('club_code');
        $club->club_name=$req->input('club_name');
        $club->cover_image=$req->file('cover_image')->store('assets');
        $club->vision_content=$req->input('vision_content');
        $club->club_logo=$req->file('club_logo')->store('assets');
        $club->mission_content=$req->input('mission_content');
        $club->club_post_image=$req->file('club_post_image')->store('assets');
        // return $club;
        if ($club->save()) {
            // If record creation is successful
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Club has been added successfully.'
                ],
                'response' => $club
            ];
            return response()->json($response);
        } else {
            // If there's an error in record creation
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Failed to add club.'
                ]
            ]);
        }
    }
}
