<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Applications;

class ApplicationsController extends Controller
{
    // POSTS REQUESTS
    function addApplication(Request $req){
        $application = new Applications;
        $application->firstname=$req->input('firstname');
        $application->middlename=$req->input('middlename');
        $application->lastname=$req->input('lastname');
        $application->email=$req->input('email');
        $application->number=$req->input('number');
        // $application->application_file=$req->input('application_file');
        $application->application_file=$req->file('application_file')->store('magiting_laguna/applications');
        $application->club_id=$req->input('club_id');
        // return $project;
        if ($application->save()) {
            // If record creation is successful
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Application has been submitted successfully.'
                ],
                'response' => $application
            ];
            return response()->json($response);
        } else {
            // If there's an error in record creation
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Failed to submit application.'
                ]
            ]);
        }
    }

    //GET REQUESTS
    function getApplications($club_id){
        $applications = Applications::where('club_id', $club_id)->get();
        return response()->json($applications);
    }
}
