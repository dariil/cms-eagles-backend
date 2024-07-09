<?php

namespace App\Http\Controllers;

use App\Models\Announcement_Archive;
use App\Models\Project_Archive;
use App\Models\Official_Archive;
use App\Models\Application_Archive;
use App\Models\Member_Applications_Archive;
use App\Models\Account_Archive;

use App\Models\Announcement;
use App\Models\Project;
use App\Models\Officers;
use App\Models\Applications;
use App\Models\Member_Applications;
use App\Models\User;
use App\Models\Club;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ArchivesController extends Controller
{
    //GET FUNCTIONS
    function getAllArchivedUsers() {
        $users = Account_Archive::with('club:club_id,club_name')
            ->get()
            ->map(function ($user) {
                return [
                    'user_id' => $user->user_id,
                    'club_id' => $user->club_id,
                    'first_name' => $user->first_name,
                    'middle_name' => $user->middle_name,
                    'last_name' => $user->last_name,
                    'number' => $user->number,
                    'email' => $user->email,
                    'access_level' => $user->access_level,
                    'date_created' => $user->date_created,
                    'club_name' => $user->club->club_name,
                ];
            });
        return response()->json($users);
    }

    function getOneUser($user_id){
        $users = Account_Archive::where('user_id', $user_id)->get();
        return response()->json($users);
    }

    // function getUsers($access_level){
    //     $users = User::where('access_level', $access_level)
    //              ->with('club:club_id,club_name')
    //              ->get()
    //              ->map(function ($user) {
    //                  return [
    //                      'user_id' => $user->user_id,
    //                      'club_id' => $user->club_id,
    //                      'first_name' => $user->first_name,
    //                      'middle_name' => $user->middle_name,
    //                      'last_name' => $user->last_name,
    //                      'number' => $user->number,
    //                      'email' => $user->email,
    //                      'access_level' => $user->access_level,
    //                      'date_created' => $user->date_created,
    //                      'club_id' => $user->club->club_id,
    //                      'club_name' => $user->club->club_name,
    //                  ];
    //              });

    //     return response()->json($users);
    //     // return User::all();
    // }

    function getArchivedAnnouncements($club_id){
        $announcements = Announcement_Archive::where('club_id', $club_id)->get();
        return response()->json($announcements);
    }

    function getArchivedProjects($club_id){
        $projects = Project_Archive::where('club_id', $club_id)->get();
        return response()->json($projects);
    }
    function getArchivedOfficers($club_id){
        $officers = Official_Archive::where('club_id', $club_id)->get();
        return response()->json($officers);
    }

    function getArchivedApplications($club_id){
        $applications = Application_Archive::where('club_id', $club_id)->get();
        return response()->json($applications);
    }

    function getMemberArchivedApplications($club_id){
        $applications = Member_Applications_Archive::where('club_id', $club_id)->get();
        return response()->json($applications);
    }

    // MOVE TO ARCHIVE FUNCTION APIs
    function archiveUser($user_id, Request $request){
        try {
            DB::beginTransaction();

            // Fetch the announcement
            $user = DB::table('tbl_users')
                ->where('user_id', $user_id)
                ->first();

            if (!$user) {
                $response = [
                    'messages' => [
                        'status' => 1,
                        'message' => 'User not found'
                    ],
                ];
                return response()->json($response);
            }

            // Insert into archived table
            DB::table('tbl_archived_accounts')->insert([
                'user_id' => $user->user_id,
                'club_id' => $user->club_id,
                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,
                'last_name' => $user->last_name,
                'number' => $user->number,
                'email' => $user->email,
                'password' => $user->password,
                'access_level' => $user->access_level,
                'date_created' => $user->date_created,
            ]);

            // Delete from original table
            DB::table('tbl_users')
                ->where('user_id', $user_id)
                ->delete();

            DB::commit();

            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Account archived successfully'
                ],
                'response' => $user
            ];

            return response()->json($response);

            // return response()->json(['message' => 'Announcement archived successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while archiving the user: ' . $e->getMessage()], 500);
        }
    }

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

    function archiveOfficer($officer_id, Request $request){
        try {
            DB::beginTransaction();

            // Fetch the announcement
            $officer = DB::table('tbl_officials')
                ->where('official_id', $officer_id)
                ->first();

            if (!$officer) {
                $response = [
                    'messages' => [
                        'status' => 1,
                        'message' => 'Officer not found'
                    ],
                ];
                return response()->json($response);
            }

            // Insert into archived table
            DB::table('tbl_archived_officials')->insert([
                'official_id' => $officer->official_id,
                'club_id' => $officer->club_id,
                'official_name' => $officer->official_name,
                'official_position' => $officer->official_position,
                'official_image' => $officer->official_image,
                'official_description' => $officer->official_description,
            ]);

            // Delete from original table
            DB::table('tbl_officials')
                ->where('official_id', $officer_id)
                ->delete();

            DB::commit();

            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Officer has been archived successfully'
                ],
                'response' => $officer
            ];

            return response()->json($response);

            // return response()->json(['message' => 'Announcement archived successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while archiving the officer: ' . $e->getMessage()], 500);
        }
    }

    function archiveApplication($application_id, Request $request){
        try {
            DB::beginTransaction();

            // Fetch the announcement
            $application = DB::table('tbl_applications_aspirants')
                ->where('application_id', $application_id)
                ->first();

            if (!$application) {
                $response = [
                    'messages' => [
                        'status' => 1,
                        'message' => 'Application not found'
                    ],
                ];
                return response()->json($response);
            }

            // Insert into archived table
            DB::table('tbl_archived_applications')->insert([
                'application_id' => $application->application_id,
                'firstname' => $application->firstname,
                'middlename' => $application->middlename,
                'lastname' => $application->lastname,
                'email' => $application->email,
                'number' => $application->number,
                'application_file' => $application->application_file,
                'club_id' => $application->club_id,
            ]);

            // Delete from original table
            DB::table('tbl_applications_aspirants')
                ->where('application_id', $application_id)
                ->delete();

            DB::commit();

            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Application has been archived successfully'
                ],
                'response' => $application
            ];

            return response()->json($response);

            // return response()->json(['message' => 'Announcement archived successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while archiving the application: ' . $e->getMessage()], 500);
        }
    }

    function archiveMemberApplication($application_id, Request $request){
        try {
            DB::beginTransaction();

            // Fetch the announcement
            $application = DB::table('tbl_applications_members')
                ->where('member_application_id', $application_id)
                ->first();

            if (!$application) {
                $response = [
                    'messages' => [
                        'status' => 1,
                        'message' => 'Application not found'
                    ],
                ];
                return response()->json($response);
            }

            // Insert into archived table
            DB::table('tbl_archived_member_applications')->insert([
                'member_application_id' => $application->member_application_id,
                'firstname' => $application->firstname,
                'middlename' => $application->middlename,
                'lastname' => $application->lastname,
                'email' => $application->email,
                'number' => $application->number,
                'application_file' => $application->application_file,
                'club_id' => $application->club_id,
                'position' => $application->position,
            ]);

            // Delete from original table
            DB::table('tbl_applications_members')
                ->where('member_application_id', $application_id)
                ->delete();

            DB::commit();

            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Application has been archived successfully'
                ],
                'response' => $application
            ];

            return response()->json($response);

            // return response()->json(['message' => 'Announcement archived successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while archiving the application: ' . $e->getMessage()], 500);
        }
    }

    //MOVE BACK TO POSTS FUNCTION APIs
    function restoreUser($user_id, Request $request){
        try {
            DB::beginTransaction();
    
            // Fetch the archived announcement
            $archivedUser = Account_Archive::findOrFail($user_id);
    
            // Create a new Announcement
            $newUser = new User([
                'club_id' => $archivedUser->club_id,
                'first_name' => $archivedUser->first_name,
                'middle_name' => $archivedUser->middle_name,
                'last_name' => $archivedUser->last_name,
                'number' => $archivedUser->number,
                'email' => $archivedUser->email,
                'password' => $archivedUser->password,
                'access_level' => $archivedUser->access_level,
            ]);
    
            // Save the new announcement
            $newUser->save();
    
            // // Update timestamps if needed
            // $newAnnouncement->created_at = $archivedAnnouncement->created_at;
            // $newAnnouncement->updated_at = $archivedAnnouncement->updated_at;
            // $newAnnouncement->save();
    
            // Delete the archived announcement
            $archivedUser->delete();
    
            DB::commit();
    
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'User restored successfully'
                ],
                'response' => $newUser
            ];
    
            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'An error occurred while restoring the user: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

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

    function restoreOfficer($officer_id, Request $request){
        try {
            DB::beginTransaction();
    
            // Fetch the archived announcement
            $archivedOfficial = Official_Archive::findOrFail($officer_id);
    
            // Create a new Announcement
            $newOfficial = new Officers([
                'club_id' => $archivedOfficial->club_id,
                'official_name' => $archivedOfficial->official_name,
                'official_position' => $archivedOfficial->official_position,
                'official_image' => $archivedOfficial->official_image,
                'official_description' => $archivedOfficial->official_description,
            ]);
    
            // Save the new announcement
            $newOfficial->save();
    
            // Delete the archived announcement
            $archivedOfficial->delete();
    
            DB::commit();
    
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Officer has been restored successfully'
                ],
                'response' => $newOfficial
            ];
    
            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'An error occurred while restoring the officer: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

    function restoreApplication($application_id, Request $request){
        try {
            DB::beginTransaction();
    
            // Fetch the archived announcement
            $archivedApplication = Application_Archive::findOrFail($application_id);
    
            // Create a new Announcement
            $newApplication = new Applications([
                'firstname' => $archivedApplication->firstname,
                'middlename' => $archivedApplication->middlename,
                'lastname' => $archivedApplication->lastname,
                'email' => $archivedApplication->email,
                'number' => $archivedApplication->number,
                'application_file' => $archivedApplication->application_file,
                'club_id' => $archivedApplication->club_id,
            ]);
    
            // Save the new announcement
            $newApplication->save();
    
            // Delete the archived announcement
            $archivedApplication->delete();
    
            DB::commit();
    
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Application has been restored successfully'
                ],
                'response' => $newApplication
            ];
    
            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'An error occurred while restoring the application: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

    function restoreMemberApplication($application_id, Request $request){
        try {
            DB::beginTransaction();
    
            // Fetch the archived announcement
            $archivedApplication = Member_Applications_Archive::findOrFail($application_id);
    
            // Create a new Announcement
            $newApplication = new Member_Applications([
                'firstname' => $archivedApplication->firstname,
                'middlename' => $archivedApplication->middlename,
                'lastname' => $archivedApplication->lastname,
                'email' => $archivedApplication->email,
                'number' => $archivedApplication->number,
                'application_file' => $archivedApplication->application_file,
                'club_id' => $archivedApplication->club_id,
                'position' => $archivedApplication->position
            ]);
    
            // Save the new announcement
            $newApplication->save();
    
            // Delete the archived announcement
            $archivedApplication->delete();
    
            DB::commit();
    
            $response = [
                'messages' => [
                    'status' => 1,
                    'message' => 'Application has been restored successfully'
                ],
                'response' => $newApplication
            ];
    
            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'messages' => [
                    'status' => 0,
                    'message' => 'An error occurred while restoring the application: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

    //DELETE APIs
    function deleteUser($user_id){
        $result = Account_Archive::where('user_id', $user_id)->delete();
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

    function deleteProject($project_id){
        $result = Project_Archive::where('project_id', $project_id)->delete();
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
        $result = Official_Archive::where('official_id', $official_id)->delete();
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

    function deleteApplication($application_id){
        $result = Application_Archive::where('application_id', $application_id)->delete();
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

    function deleteMemberApplication($application_id){
        $result = Member_Applications_Archive::where('member_application_id', $application_id)->delete();
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
