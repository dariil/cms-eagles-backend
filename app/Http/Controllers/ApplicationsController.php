<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Applications;
use App\Models\Member_Applications;
use Illuminate\Support\Facades\File; // Add this import statement
use Illuminate\Support\Facades\Storage;
use mikehaertl\pdftk\Pdf;
use Illuminate\Support\Facades\DB;

use App\Mail\AspirantSuccessMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ApplicationsController extends Controller
{
    // POSTS REQUESTS
    function addApplication(Request $req) {
        $application = new Applications;
        $application_id_config = ['table'=>'tbl_applications_aspirants', 'field'=>'application_id','length'=>10,'prefix'=>'AAP'];
        $application->application_id=IdGenerator::generate($application_id_config);
        $application->firstname = $req->input('firstname');
        $application->middlename = $req->input('middlename');
        $application->lastname = $req->input('lastname');
        $application->email = $req->input('email');
        $application->number = $req->input('number');
        $application->application_file = $req->file('application_file')->store('magiting_laguna/applications');
        $application->club_id = $req->input('club_id');
    
        if ($application->save()) {
            // Fetch the club name
            $club = DB::table('tbl_clubs')
                ->where('club_id', $application->club_id)
                ->first();
    
            // Prepare email data
            $emailData = [
                'application' => $application,
                'club_name' => $club->club_name,
            ];
    
            // Send email after successful save
            Mail::to($application->email)->send(new AspirantSuccessMail($emailData));
    
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

    function addMemberApplication(Request $req){
        $application = new Member_Applications;
        $application_member_id_config = ['table'=>'tbl_applications_members', 'field'=>'member_application_id','length'=>10,'prefix'=>'AAM'];
        $application->member_application_id=IdGenerator::generate($application_member_id_config);
        $application->firstname=$req->input('firstname');
        $application->middlename=$req->input('middlename');
        $application->lastname=$req->input('lastname');
        $application->email=$req->input('email');
        $application->number=$req->input('number');
        $application->application_file=$req->file('application_file')->store('magiting_laguna/applications');
        $application->club_id=$req->input('club_id');
        $application->position=$req->input('position');
        // return $project;
        if ($application->save()) {
            // Fetch the club name
            $club = DB::table('tbl_clubs')
                ->where('club_id', $application->club_id)
                ->first();
    
            // Prepare email data
            $emailData = [
                'application' => $application,
                'club_name' => $club->club_name,
            ];
    
            // Send email after successful save
            Mail::to($application->email)->send(new AspirantSuccessMail($emailData));

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

    public function getSevenRecentApplications($club_id)
    {
        $applications = Applications::orderBy('created_at', 'desc')
            ->where('club_id', $club_id)
            ->take(7)
            ->get();

        $data = $this->formatData($applications);

        return response()->json($data);
    }

    private function formatData($applications)
    {
        $dates = [];
        
        // Count the number of applications for each date
        foreach ($applications as $application) {
            $date = Carbon::parse($application->created_at)->format('Y-m-d');
            if (!isset($dates[$date])) {
                $dates[$date] = 0;
            }
            $dates[$date]++;
        }

        // Prepare labels and values arrays
        $labels = array_keys($dates);
        $values = array_values($dates);

        // Ensure labels and values are sorted by date
        array_multisort($labels, SORT_ASC, $values);

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    function getMemberApplications($club_id){
        $applications = Member_Applications::where('club_id', $club_id)->get();
        return response()->json($applications);
    }

    function getSevenRecentMemberApplications($club_id){
        $applications = Member_Applications::orderBy('created_at', 'desc')->where('club_id', $club_id)->take(7)->get();
        return response()->json($applications);
    }

    function getOneApplication($application_id){
        $application = Applications::where('application_id', $application_id)->get();
        return response()->json($application);
    }

    function getOneMemberApplication($application_id){
        $application = Member_Applications::where('member_application_id', $application_id)->get();
        return response()->json($application);
    }

    public function getPdf($applicationID)
    {
        // Fetch the application data based on the provided $applicationID
        $application = Applications::findOrFail($applicationID);

        // Get the file path or URL for the application's PDF
        $filePath = $application->application_file;
        $filePath = str_replace('magiting_laguna/', '', $filePath);

        // Check if the file exists
        if (file_exists(public_path($filePath))) {
            $file = public_path($filePath);
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
            ];

            return response()->file($file, $headers);
        } else {
            echo $filePath;
            // Handle the case when the file doesn't exist
            return response()->json(['error' => 'File not found']);
        }
    }

    public function getMemberPdf($applicationID)
    {
        // Fetch the application data based on the provided $applicationID
        $application = Member_Applications::findOrFail($applicationID);

        // Get the file path or URL for the application's PDF
        $filePath = $application->application_file;
        $filePath = str_replace('magiting_laguna/', '', $filePath);

        // Check if the file exists
        if (file_exists(public_path($filePath))) {
            $file = public_path($filePath);
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
            ];

            return response()->file($file, $headers);
        } else {
            echo $filePath;
            // Handle the case when the file doesn't exist
            return response()->json(['error' => 'File not found']);
        }
    }

    function countAspirants($club_id)
    {
        $count = DB::table('tbl_applications_aspirants')
            ->where('club_id', $club_id)
            ->whereDate('created_at', Carbon::today())
            ->count();
        
        return response()->json(['count' => $count]);
    }

    function countMembers($club_id)
    {
        $count = DB::table('tbl_applications_members')->where('club_id', $club_id)->count();
        return response()->json(['count' => $count]);
    }

    //UPDATE REQUESTS
    function updateApplication($application_id, Request $req){
        $application = Applications::find($application_id);
        
        // Update other fields
        $application->firstname = $req->input('firstname');
        $application->middlename = $req->input('middlename');
        $application->lastname = $req->input('lastname');
        $application->email = $req->input('email');
        $application->number = $req->input('number');
        $application->club_id = $req->input('club_id');
        // $application->position = $req->input('position');
        
        // Handle file replacement if a new file is uploaded
        if ($req->hasFile('application_file')) {
            // Delete the old file
            if ($application->application_file) {
                Storage::delete($application->application_file);
            }
            
            // Store the new file
            $application->application_file = $req->file('application_file')->store('magiting_laguna/applications');
        }
        
        if ($application->save()) {
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Application has been updated successfully.'
                ],
                'response' => $application
            ];
            return response()->json($response);
        } else {
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Failed to update application.'
                ]
            ]);
        }
    }

    function updateMemberApplication($application_id, Request $req){
        $application = Member_Applications::find($application_id);
        
        // Update other fields
        $application->firstname = $req->input('firstname');
        $application->middlename = $req->input('middlename');
        $application->lastname = $req->input('lastname');
        $application->email = $req->input('email');
        $application->number = $req->input('number');
        $application->club_id = $req->input('club_id');
        $application->position = $req->input('position');
        
        // Handle file replacement if a new file is uploaded
        if ($req->hasFile('application_file')) {
            // Delete the old file
            if ($application->application_file) {
                Storage::delete($application->application_file);
            }
            
            // Store the new file
            $application->application_file = $req->file('application_file')->store('magiting_laguna/applications');
        }
        
        if ($application->save()) {
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Application has been updated successfully.'
                ],
                'response' => $application
            ];
            return response()->json($response);
        } else {
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Failed to update application.'
                ]
            ]);
        }
    }


    //DELETE REQUESTS
    function deleteApplication($application_id){
        $result = Applications::where('application_id', $application_id)->delete();
        if($result){
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Application has been deleted permanently.'
                ],
                'response' => $result
            ];
            return response()->json($response);
        } else{
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Failed to delete application.'
                ]
            ]);
        }
    }
}
