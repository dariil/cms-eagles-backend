<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;

    protected $table = 'tbl_clubs'; //THIS LINE LETS LARAVEL KNOW THAT THE USERS TABLE IS NAMES AS tbl_users
    protected $primaryKey = 'club_id'; // THIS LINE LETS LARAVEL KNOW THAT THIS IS THE PRIMARY KEY
    protected $fillable = [
        'club_code',
        'club_name',
        'cover_image',
        'vision_content',
        'club_logo',
        'mission_content',
        'club_post_image',
    ];

    public $timestamps = false;
}
