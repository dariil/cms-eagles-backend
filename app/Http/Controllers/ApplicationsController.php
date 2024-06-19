<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Applications;
use Illuminate\Support\Facades\File; // Add this import statement
use mikehaertl\pdftk\Pdf;

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

    function getOneApplication($application_id){
        $application = Applications::where('application_id', $application_id)->get();
        return response()->json($application);
    }

    function getApplicationDetails($application_file){
        $path = 'http://127.0.0.1:8000/applications/' . $application_file;
        $path = trim($path);
        // return $path;
        // return $path;
        // if (!File::exists($path)) {
        //     return response()->json(['error' => 'File not found.'], 404);
        // }

        try {
            $pdf = new Pdf($path);
            // $result = $pdf->allow('AllFeatures');
            $formFields = $pdf->getDataFields();

            if ($formFields === false) {
                $error = $pdf->getError();
            }

            return response()->json([
                'filename' => $path,
                'form_fields' => $formFields,
                'error' => $error,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
