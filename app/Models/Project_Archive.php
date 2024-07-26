<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_Archive extends Model
{
    use HasFactory;

    protected $table = 'tbl_archived_projects'; //THIS LINE LETS LARAVEL KNOW THAT THE USERS TABLE IS NAMES AS tbl_users
    public $incrementing = false;
    protected $primaryKey = 'project_id'; // THIS LINE LETS LARAVEL KNOW THAT THIS IS THE PRIMARY KEY
    protected $fillable = [
        'project_id',
        'club_id',
        'project_title',
        'project_description',
        'cover_image',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    public $timestamps = false;
}
