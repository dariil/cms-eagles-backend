<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\ContentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//POST
Route::post('addUser',[UserController::class, 'addUser']);
Route::post('login',[UserController::class, 'login']);
Route::post('addClub',[ClubController::class, 'addClub']);
Route::post('addAnnouncement/{club_id}',[ContentController::class, 'addAnnouncement']);
Route::post('addHome',[ContentController::class, 'addHome']);
Route::post('addProjects',[ContentController::class, 'addProjects']);
Route::post('addOfficers/{club_id}',[ContentController::class, 'addOfficers']);

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

//UNDER MAINTENANCE GET
// Route::get('getUsersInClub/{club_id}/{access_level}',[UserController::class, 'getUsersInClub']);

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