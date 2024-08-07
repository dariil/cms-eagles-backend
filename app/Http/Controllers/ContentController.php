<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Club;
use App\Models\Project;
use App\Models\Home;
use App\Models\Officers;
use App\Models\Applications;
use App\Models\Member_Applications;
use Carbon\Carbon;

use App\Mail\AnnouncementMail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ContentController extends Controller
{
    //
    //   POSTS REQUESTS //////////////////////////////////////
    function addHome(Request $req){
        $home = new Home;
        $home_id_config = ['table'=>'tbl_home', 'field'=>'home_id','length'=>10,'prefix'=>'HOME-'];
        $home->home_id=IdGenerator::generate($home_id_config);
        $home->club_id=$req->input('club_id');
        $home->logo=$req->file('logo')->store('assets');
        $home->description=$req->input('description');
        $home->hero_title=$req->input('hero_title');
        $home->hero_video=$req->file('hero_video')->store('assets');
        $home->hero_tagline=$req->input('hero_tagline');
        // $home->updated_by=$req->input('updated_by');

        if ($home->save()) {
            // If record creation is successful
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Home content has been posted successfully.'
                ],
                'response' => $home
            ];
            return response()->json($response);
        } else {
            // If there's an error in record creation
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Failed to post home content.'
                ]
            ]);
        }
    }

    // TEST AREA

    function getAllAspirantEmail($club_id){
        $applications = Applications::where('club_id', $club_id)->get();
        $emails = $applications->pluck('email');
        $oneApplication = Applications::where('application_id', "AAP0000001")->get();

        $emailData = [
            'application' => $oneApplication,
        ];

        // Send email after successful save
        Mail::to($emails)->send(new AnnouncementMail($emailData));
        // $this->sendSms($req->input('number'), 'Hello, this is a test message!');

        return response()->json($emails);
    }

    function getAllMemberEmail($club_id){
        $applications = Member_Applications::where('club_id', $club_id)->get();
        $emails = $applications->pluck('email');
        return response()->json($emails);
    }

    // TEST AREA END

    function addAnnouncement($club_id, Request $req){
        $announcement = new Announcement;
        $announcement_id_config = ['table'=>'tbl_announcements', 'field'=>'announcement_id','length'=>10,'prefix'=>'ANC-'];
        $announcement->announcement_id=IdGenerator::generate($announcement_id_config);
        $announcement->club_id=$club_id;
        $announcement->title=$req->input('title');
        $announcement->description=$req->input('description');
        $announcement->cover_image=$req->file('cover_image')->store('assets');
        $announcement->created_by=$req->input('created_by');

        if ($announcement->save()) {

            $applications = Applications::where('club_id', $club_id)->get();
            $emails = $applications->pluck('email');

            $club = DB::table('tbl_clubs')
                ->where('club_id', $announcement->club_id)
                ->first();

            $emailData = [
                'announcement' => $announcement,
                'club_code' => $club->club_code,
            ];

            // Send email after successful save
            Mail::to($emails)->send(new AnnouncementMail($emailData));

            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Project has been posted successfully.'
                ],
                'response' => $announcement
            ];
            return response()->json($response);
        } else {
            // If there's an error in record creation
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Failed to post project.'
                ]
            ]);
        }
    }

    function addProjects(Request $req){
        $project = new Project;
        $project_id_config = ['table'=>'tbl_projects', 'field'=>'project_id','length'=>10,'prefix'=>'PRJ-'];
        $project->project_id=IdGenerator::generate($project_id_config);
        $project->club_id=$req->input('club_id');
        $project->project_title=$req->input('project_title');
        $project->project_description=$req->input('project_description');
        $project->cover_image=$req->file('cover_image')->store('assets');
        $project->created_by=$req->input('created_by');
        // return $project;
        if ($project->save()) {
            // If record creation is successful
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Announcement has been posted successfully.'
                ],
                'response' => $project
            ];
            return response()->json($response);
        } else {
            // If there's an error in record creation
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Failed to post announcement.'
                ]
            ]);
        }
    }

    function addOfficers($club_id, Request $req){
        $officers = new Officers;
        $officer_id_config = ['table'=>'tbl_officials', 'field'=>'official_id','length'=>10,'prefix'=>'OFC-'];
        $officers->official_id=IdGenerator::generate($officer_id_config);
        // $officers->club_id=$req->input('club_id');
        $officers->club_id=$club_id;
        $officers->official_name=$req->input('official_name');
        $officers->official_position=$req->input('official_position');
        $officers->official_image=$req->file('official_image')->store('assets');
        $officers->official_description=$req->input('official_description');
        // return $project;
        if ($officers->save()) {
            // If record creation is successful
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Officer has been added successfully.'
                ],
                'response' => $officers
            ];
            return response()->json($response);
        } else {
            // If there's an error in record creation
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Failed to add officer.'
                ]
            ]);
        }
    }

    //   GET REQUESTS //////////////////////////////////////
    function getAnnouncementsInClub($club_id){
        $announcements = Announcement::where('club_id', $club_id)->get();
        return response()->json($announcements);
    }

    function getOneAnnouncement($announcement_id){
        $announcements = Announcement::where('announcement_id', $announcement_id)->get();
        return response()->json($announcements);
    }

    function getRecentAnnouncement($club_id){
        $announcement = Announcement::orderBy('created_at', 'desc')->where('club_id', $club_id)->take(2)->get();
        return response()->json($announcement);
    }

    function getRecentProject($club_id){
        $project = Project::orderBy('created_at', 'desc')->take(1)->where('club_id', $club_id)->get();
        return response()->json($project);
    }

    function getHome($club_id){
        $home = Home::where('club_id', $club_id)->get();
        return response()->json($home);
    }

    function getProjectsInClub($club_id){
        $project = Project::where('club_id', $club_id)->get();
        return response()->json($project);
    }

    function getOneProject($project_id){
        $project = Project::where('project_id', $project_id)->get();
        return response()->json($project);
    }

    function getAboutClub($club_id){
        $about = Club::where('club_id', $club_id)->get();
        return response()->json($about);
    }

    function getOfficials($club_id){
        $officers = Officers::where('club_id', $club_id)->get();
        return response()->json($officers);
    }

    function getOneOfficer($officer_id){
        $officers = Officers::where('official_id', $officer_id)->get();
        return response()->json($officers);
    }

    function countAnnouncements($club_id)
    {
        $count = DB::table('tbl_announcements')->where('club_id', $club_id)->count();
        return response()->json(['count' => $count]);
    }

    function countProjects($club_id)
    {
        $count = DB::table('tbl_projects')->where('club_id', $club_id)->count();
        return response()->json(['count' => $count]);
    }

    function countOfficers($club_id)
    {
        $count = DB::table('tbl_officials')->where('club_id', $club_id)->count();
        return response()->json(['count' => $count]);
    }

    function getFiveRecentAnnouncement($club_id){
        $announcement = Announcement::orderBy('created_at', 'desc')->where('club_id', $club_id)->take(5)->get();
        return response()->json($announcement);
    }

    function getFiveRecentProjects($club_id){
        $project = Project::orderBy('created_at', 'desc')->where('club_id', $club_id)->take(5)->get();
        return response()->json($project);
    }

    function getFiveRecentOfficers($club_id){
        $officers = Officers::orderBy('created_at', 'desc')->where('club_id', $club_id)->take(5)->get();
        return response()->json($officers);
    }

    //   UPDATE REQUESTS //////////////////////////////////////
    function updateHome($home_id, Request $req){
        $home = Home::find($home_id);
        $home->hero_title = $req->input('home_title');
        $home->hero_tagline = $req->input('home_tagline');
        $home->description = $req->input('description');
    
        // Check if a new video file was uploaded
        if ($req->hasFile('video')) {
            $home->hero_video = $req->file('video')->store('assets');
        }
    
        // Check if a new image file was uploaded
        if ($req->hasFile('image')) {
            $home->logo = $req->file('image')->store('assets');
        }
    
        $home->updated_at = Carbon::now();
    
        if ($home->save()) {
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Home contents has been updated successfully.'
                ],
                'response' => $home
            ];
            return response()->json($response);
        } else {
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Failed to update home contents.'
                ]
            ]);
        }
    }

    function updateAnnouncement($announcement_id, Request $req){
        $announcement = Announcement::find($announcement_id);
        $announcement->title=$req->input('title');
        // $announcement->cover_image=$req->file('image')->store('assets');
        $announcement->description=$req->input('description');
        $announcement->updated_at=Carbon::now();

        if ($req->hasFile('image')) {
            $announcement->cover_image=$req->file('image')->store('assets');
        }

        $announcement->save();
        if ($announcement->save()) {
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Announcement contents has been updated successfully.'
                ],
                'response' => $announcement
            ];
            return response()->json($response);
        } else {
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Failed to update announcement contents.'
                ]
            ]);
        }
    }

    function updateProjects($projects_id, Request $req){
        $projects = Project::find($projects_id);
        $projects->project_title=$req->input('project_title');
        $projects->project_description=$req->input('project_description');
        $projects->updated_at=Carbon::now();

        if ($req->hasFile('image')) {
            $projects->cover_image=$req->file('image')->store('assets');
        }

        $projects->save();
        if ($projects->save()) {
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Project contents has been updated successfully.'
                ],
                'response' => $projects
            ];
            return response()->json($response);
        } else {
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Failed to update project contents.'
                ]
            ]);
        }
    }

    function updateAbout($club_id, Request $req){
        $about = Club::find($club_id);
        $about->club_name=$req->input('club_name');
        // $about->cover_image=$req->file('cover_image')->store('assets');
        $about->vision_content=$req->input('vision_content');
        // $about->club_logo=$req->file('logo')->store('assets');
        if ($req->hasFile('logo')) {
            $about->club_logo=$req->file('logo')->store('assets');
        }
        $about->mission_content=$req->input('mission_content');
        // $about->club_post_image=$req->file('post_image')->store('assets');
        if ($req->hasFile('post_image')) {
            $about->club_post_image=$req->file('post_image')->store('assets');
        }
        // $about->updated_at=Carbon::now();
        $about->save();
        if ($about->save()) {
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'About contents has been updated successfully.'
                ],
                'response' => $about
            ];
            return response()->json($response);
        } else {
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Failed to update about contents.'
                ]
            ]);
        }
    }

    function updateOfficer($official_id, Request $req){
        $officer = Officers::find($official_id);
        $officer->official_name=$req->input('official_name');
        $officer->official_position=$req->input('official_position');
        // $officer->official_image=$req->file('official_image')->store('assets');
        if ($req->hasFile('official_image')) {
            $officer->official_image=$req->file('official_image')->store('assets');
        }
        $officer->official_description=$req->input('official_description');
        $officer->save();
        if ($officer->save()) {
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Officer has been updated successfully.'
                ],
                'response' => $officer
            ];
            return response()->json($response);
        } else {
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Failed to update officer contents.'
                ]
            ]);
        }
    }

    //   DELETE REQUESTS //////////////////////////////////////
    function deleteAnnouncement($announcement_id){
        $result = Announcement::where('announcement_id', $announcement_id)->delete();
        if($result){
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Announcement has been deleted permanently.'
                ],
                'response' => $result
            ];
            return response()->json($response);
        } else{
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Failed to delete announcement.'
                ]
            ]);
        }
    }

    function deleteProject($project_id){
        $result = Project::where('project_id', $project_id)->delete();
        if($result){
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Project has been deleted permanently.'
                ],
                'response' => $result
            ];
            return response()->json($response);
        } else{
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Failed to delete project.'
                ]
            ]);
        }
    }

    function deleteOfficer($official_id){
        $result = Officers::where('official_id', $official_id)->delete();
        if($result){
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Officer has been deleted permanently.'
                ],
                'response' => $result
            ];
            return response()->json($response);
        } else{
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'Failed to delete officer.'
                ]
            ]);
        }
    }
}
