<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\ApplicationsController;
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
//
Route::post('expLogin',[UserController::class,'userLogin']);

//GET
Route::get('getAnnouncementsInClub/{club_id}',[ContentController::class, 'getAnnouncementsInClub']);
Route::get('getProjectsInClub/{club_id}',[ContentController::class, 'getProjectsInClub']);
Route::get('getUsers/{access_level}',[UserController::class, 'getUsers']);
Route::get('getAdmin/{access_level}',[UserController::class, 'getAdmin']);
Route::get('getOneUser/{user_id}',[UserController::class, 'getOneUser']);
Route::get('getHome/{club_id}',[ContentController::class, 'getHome']);
Route::get('getAboutClub/{club_id}',[ContentController::class, 'getAboutClub']);
Route::get('getOfficials/{club_id}',[ContentController::class, 'getOfficials']);
Route::get('getRecentAnnouncement/{club_id}',[ContentController::class, 'getRecentAnnouncement']);
Route::get('getApplications/{club_id}',[ApplicationsController::class, 'getApplications']);
Route::middleware(\App\Http\Middleware\CorsMiddleware::class)->group(function () {
    Route::get('getOneApplication/{club_id}', [ApplicationsController::class, 'getOneApplication']);
    // Other routes
});
Route::get('getApplicationDetails/{filename}', [ApplicationsController::class, 'getApplicationDetails']);

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});




//UNDER MAINTENANCE GET
Route::get('getUsersInClub/{club_id}/{access_level}',[UserController::class, 'getUsersInClub']);

//UPDATE
Route::post('updateHome/{home_id}',[ContentController::class, 'updateHome']);
Route::post('updateAnnouncement/{announcement_id}',[ContentController::class, 'updateAnnouncement']);
Route::post('updateProjects/{projects_id}',[ContentController::class, 'updateProjects']);
Route::post('updateAbout/{club_id}',[ContentController::class, 'updateAbout']);
Route::post('updateOfficer/{official_id}',[ContentController::class, 'updateOfficer']);
Route::post('updateUser/{user_id}',[UserController::class, 'updateUser']);

//DELETE
Route::delete('deleteUser/{user_id}',[UserController::class, 'deleteUser']);
Route::delete('deleteAnnouncement/{announcement_id}',[ContentController::class, 'deleteAnnouncement']);
Route::delete('deleteProject/{project_id}',[ContentController::class, 'deleteProject']);
Route::delete('deleteOfficer/{officer_id}',[ContentController::class, 'deleteOfficer']);