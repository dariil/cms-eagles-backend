<?php

namespace App\Http\Controllers;

use App\Models\Announcement_Archive;
use App\Models\Project_Archive;

use App\Models\Announcement;
use App\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArchivesController extends Controller
{
    //GET FUNCTIONS
    function getArchivedAnnouncements($club_id){
        $announcements = Announcement_Archive::where('club_id', $club_id)->get();
        return response()->json($announcements);
    }

    function getArchivedProjects($club_id){
        $projects = Project_Archive::where('club_id', $club_id)->get();
        return response()->json($projects);
    }

    // MOVE TO ARCHIVE FUNCTION APIs
    function archiveAnnouncement($announcement_id, Request $request){
        try {
            DB::beginTransaction();

            // Fetch the announcement
            $announcement = DB::table('tbl_announcements')
                ->where('announcement_id', $announcement_id)
                ->first();

            if (!$announcement) {
                $response = [
                    'messages' => [
                        'status' => 1,
                        'message' => 'Announcement not found'
                    ],
                ];
                return response()->json($response);
            }

            // Insert into archived table
            DB::table('tbl_archived_announcements')->insert([
                'announcement_id' => $announcement->announcement_id,
                'club_id' => $announcement->club_id,
                'title' => $announcement->title,
                'description' => $announcement->description,
                'cover_image' => $announcement->cover_image,
                'created_at' => $announcement->created_at,
                'updated_at' => $announcement->updated_at,
                'created_by' => $announcement->created_by,
                'updated_by' => $announcement->updated_by,
            ]);

            // Delete from original table
            DB::table('tbl_announcements')
                ->where('announcement_id', $announcement_id)
                ->delete();

            DB::commit();

            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Announcement archived successfully'
                ],
                'response' => $announcement
            ];

            return response()->json($response);

            // return response()->json(['message' => 'Announcement archived successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while archiving the announcement: ' . $e->getMessage()], 500);
        }
    }

    function archiveProject($project_id, Request $request){
        try {
            DB::beginTransaction();

            // Fetch the announcement
            $project = DB::table('tbl_projects')
                ->where('project_id', $project_id)
                ->first();

            if (!$project) {
                $response = [
                    'messages' => [
                        'status' => 1,
                        'message' => 'Project not found'
                    ],
                ];
                return response()->json($response);
            }

            // Insert into archived table
            DB::table('tbl_archived_projects')->insert([
                'project_id' => $project->project_id,
                'club_id' => $project->club_id,
                'project_title' => $project->project_title,
                'project_description' => $project->project_description,
                'cover_image' => $project->cover_image,
                'created_at' => $project->created_at,
                'updated_at' => $project->updated_at,
                'created_by' => $project->created_by,
                'updated_by' => $project->updated_by,
            ]);

            // Delete from original table
            DB::table('tbl_projects')
                ->where('project_id', $project_id)
                ->delete();

            DB::commit();

            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Project archived successfully'
                ],
                'response' => $project
            ];

            return response()->json($response);

            // return response()->json(['message' => 'Announcement archived successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while archiving the project: ' . $e->getMessage()], 500);
        }
    }

    //MOVE BACK TO POSTS FUNCTION APIs
    function restoreAnnouncement($announcement_id, Request $request){
        try {
            DB::beginTransaction();
    
            // Fetch the archived announcement
            $archivedAnnouncement = Announcement_Archive::findOrFail($announcement_id);
    
            // Create a new Announcement
            $newAnnouncement = new Announcement([
                'club_id' => $archivedAnnouncement->club_id,
                'title' => $archivedAnnouncement->title,
                'description' => $archivedAnnouncement->description,
                'cover_image' => $archivedAnnouncement->cover_image,
                'created_by' => $archivedAnnouncement->created_by,
            ]);
    
            // Save the new announcement
            $newAnnouncement->save();
    
            // // Update timestamps if needed
            // $newAnnouncement->created_at = $archivedAnnouncement->created_at;
            // $newAnnouncement->updated_at = $archivedAnnouncement->updated_at;
            // $newAnnouncement->save();
    
            // Delete the archived announcement
            $archivedAnnouncement->delete();
    
            DB::commit();
    
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Announcement restored successfully'
                ],
                'response' => $newAnnouncement
            ];
    
            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'An error occurred while restoring the announcement: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

    function restoreProject($project_id, Request $request){
        try {
            DB::beginTransaction();
    
            // Fetch the archived announcement
            $archivedProject = Project_Archive::findOrFail($project_id);
    
            // Create a new Announcement
            $newProject = new Project([
                'club_id' => $archivedProject->club_id,
                'project_title' => $archivedProject->project_title,
                'project_description' => $archivedProject->project_description,
                'cover_image' => $archivedProject->cover_image,
                'created_by' => $archivedProject->created_by,
            ]);
    
            // Save the new announcement
            $newProject->save();
    
            // // Update timestamps if needed
            // $newAnnouncement->created_at = $archivedAnnouncement->created_at;
            // $newAnnouncement->updated_at = $archivedAnnouncement->updated_at;
            // $newAnnouncement->save();
    
            // Delete the archived announcement
            $archivedProject->delete();
    
            DB::commit();
    
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Project restored successfully'
                ],
                'response' => $newProject
            ];
    
            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'An error occurred while restoring the project: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

    //DELETE APIs
    function deleteAnnouncement($announcement_id){
        $result = Announcement_Archive::where('announcement_id', $announcement_id)->delete();
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
}
