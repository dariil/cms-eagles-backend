<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Club;
use App\Models\Project;
use App\Models\Home;
use App\Models\Officers;
use Carbon\Carbon;

class ContentController extends Controller
{
    //
    //   POSTS REQUESTS //////////////////////////////////////
    function addHome(Request $req){
        $home = new Home;
        $home->club_id=$req->input('club_id');
        $home->logo=$req->file('logo')->store('assets');
        $home->description=$req->input('description');
        $home->updated_by=$req->input('updated_by');

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

    function addAnnouncement($club_id, Request $req){
        $announcement = new Announcement;
        $announcement->club_id=$club_id;
        $announcement->title=$req->input('title');
        $announcement->description=$req->input('description');
        $announcement->cover_image=$req->file('cover_image')->store('assets');
        $announcement->created_by=$req->input('created_by');
        // return $announcement;
        if ($announcement->save()) {
            // If record creation is successful
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

    function getAboutClub($club_id){
        $about = Club::where('club_id', $club_id)->get();
        return response()->json($about);
    }

    function getOfficials($club_id){
        $officers = Officers::where('club_id', $club_id)->get();
        return response()->json($officers);
    }

    //   UPDATE REQUESTS //////////////////////////////////////
    function updateHome($home_id, Request $req){
        $home = Home::find($home_id);
        $home->hero_title=$req->input('home_title');
        $home->hero_tagline=$req->input('home_tagline');
        $home->hero_video=$req->file('video')->store('assets');
        $home->logo=$req->file('image')->store('assets');
        $home->description=$req->input('description');
        $home->updated_at=Carbon::now();
        $home->save();
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
                    'message' => 'Failed to updated home contents.'
                ]
            ]);
        }
    }

    function updateAnnouncement($announcement_id, Request $req){
        $announcement = Announcement::find($announcement_id);
        $announcement->title=$req->input('title');
        $announcement->cover_image=$req->file('image')->store('assets');
        $announcement->description=$req->input('description');
        $announcement->updated_at=Carbon::now();
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
        $projects->cover_image=$req->file('image')->store('assets');
        $projects->project_description=$req->input('project_description');
        $projects->updated_at=Carbon::now();
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
        $about->cover_image=$req->file('cover_image')->store('assets');
        $about->vision_content=$req->input('vision_content');
        $about->club_logo=$req->file('logo')->store('assets');
        $about->mission_content=$req->input('mission_content');
        $about->club_post_image=$req->file('post_image')->store('assets');
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
        $officer->official_image=$req->file('official_image')->store('assets');
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
