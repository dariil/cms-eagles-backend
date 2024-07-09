<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\ApplicationsController;
use App\Http\Controllers\ArchivesController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\CorsMiddleware;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::options('/{any}', function () {
    return response()->json([], 200);
})->where('any', '.*');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
], function($router){
    Route::post('/getToken',[AuthController::class, 'getToken']);
});

//POST
Route::post('addUser',[UserController::class, 'addUser']);
Route::post('login',[UserController::class, 'login']);
Route::post('addClub',[ClubController::class, 'addClub']);
Route::post('addAnnouncement/{club_id}',[ContentController::class, 'addAnnouncement']);
Route::post('addHome',[ContentController::class, 'addHome']);
Route::post('addProjects',[ContentController::class, 'addProjects']);
Route::post('addOfficers/{club_id}',[ContentController::class, 'addOfficers']);
Route::post('addApplication',[ApplicationsController::class, 'addApplication']);
Route::post('addMemberApplication',[ApplicationsController::class, 'addMemberApplication']);
//
Route::post('expLogin',[UserController::class,'userLogin']);

//GET
Route::get('getUsersInClub/{club_id}/{access_level}',[UserController::class, 'getUsersInClub']);
Route::get('getAdminInClub/{club_id}/{access_level}',[UserController::class, 'getUsersInClub']);
Route::get('getAnnouncementsInClub/{club_id}',[ContentController::class, 'getAnnouncementsInClub']);
Route::get('getProjectsInClub/{club_id}',[ContentController::class, 'getProjectsInClub']);
Route::get('getUsers/{access_level}',[UserController::class, 'getUsers']);
Route::get('getAdmin/{access_level}',[UserController::class, 'getAdmin']);
Route::get('getOneUser/{user_id}',[UserController::class, 'getOneUser']);
Route::get('getHome/{club_id}',[ContentController::class, 'getHome']);
Route::get('getAboutClub/{club_id}',[ContentController::class, 'getAboutClub']);
Route::get('getOfficials/{club_id}',[ContentController::class, 'getOfficials']);
Route::get('getRecentAnnouncement/{club_id}',[ContentController::class, 'getRecentAnnouncement']);
Route::get('getRecentProject/{club_id}',[ContentController::class, 'getRecentProject']);
Route::get('getApplications/{club_id}',[ApplicationsController::class, 'getApplications']);

Route::middleware(\App\Http\Middleware\CorsMiddleware::class)->group(function () {
    Route::get('getOneApplication/{club_id}', [ApplicationsController::class, 'getOneApplication']);
});

Route::get('getApplicationDetails/{filename}', [ApplicationsController::class, 'getApplicationDetails']);
Route::get('get-pdf/{applicationID}', [ApplicationsController::class, 'getPdf']);
Route::get('getArchivedAnnouncements/{announcement_id}', [ArchivesController::class, 'getArchivedAnnouncements']);
Route::get('getArchivedProjects/{project_id}', [ArchivesController::class, 'getArchivedProjects']);
Route::get('getAllArchivedUsers', [ArchivesController::class, 'getAllArchivedUsers']);
Route::get('getOneArchivedUser/{user_id}', [ArchivesController::class, 'getOneUser']);
Route::get('getArchivedOfficers/{club_id}', [ArchivesController::class, 'getArchivedOfficers']);
Route::get('getArchivedApplications/{club_id}', [ArchivesController::class, 'getArchivedApplications']);

//FOR VERCEL DEPLOYMENT
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});

//UPDATE
Route::post('updateHome/{home_id}',[ContentController::class, 'updateHome']);
Route::post('updateAnnouncement/{announcement_id}',[ContentController::class, 'updateAnnouncement']);
Route::post('updateProjects/{projects_id}',[ContentController::class, 'updateProjects']);
Route::post('updateAbout/{club_id}',[ContentController::class, 'updateAbout']);
Route::post('updateOfficer/{official_id}',[ContentController::class, 'updateOfficer']);
Route::post('updateUser/{user_id}',[UserController::class, 'updateUser']);
Route::post('updateApplication/{application_id}',[ApplicationsController::class, 'updateApplication']);

//DELETE
Route::delete('deleteUser/{user_id}',[ArchivesController::class, 'deleteUser']);
Route::delete('deleteAnnouncement/{announcement_id}',[ArchivesController::class, 'deleteAnnouncement']);
Route::delete('deleteProject/{project_id}',[ArchivesController::class, 'deleteProject']);
Route::delete('deleteOfficer/{officer_id}',[ArchivesController::class, 'deleteOfficer']);
Route::delete('deleteApplication/{application_id}',[ArchivesController::class, 'deleteApplication']);

//ARCHIVING APIs
Route::post('archiveUser/{user_id}', [ArchivesController::class, 'archiveUser']);
Route::post('archiveAnnouncement/{announcement_id}', [ArchivesController::class, 'archiveAnnouncement']);
Route::post('archiveProject/{project_id}', [ArchivesController::class, 'archiveProject']);
Route::post('archiveOfficer/{officer_id}', [ArchivesController::class, 'archiveOfficer']);
Route::post('archiveApplication/{application_id}', [ArchivesController::class, 'archiveApplication']);

//RETURN ARCHIVED APIs
Route::post('restoreUser/{user_id}', [ArchivesController::class, 'restoreUser']);
Route::post('restoreAnnouncement/{announcement_id}', [ArchivesController::class, 'restoreAnnouncement']);
Route::post('restoreProject/{project_id}', [ArchivesController::class, 'restoreProject']);
Route::post('restoreOfficer/{officer_id}', [ArchivesController::class, 'restoreOfficer']);
Route::post('restoreApplication/{application_id}', [ArchivesController::class, 'restoreApplication']);