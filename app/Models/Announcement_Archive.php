<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement_Archive extends Model
{
    use HasFactory;

    protected $table = 'tbl_archived_announcements'; //THIS LINE LETS LARAVEL KNOW THAT THE USERS TABLE IS NAMES AS tbl_users
    public $incrementing = false;
    protected $primaryKey = 'announcement_id'; // THIS LINE LETS LARAVEL KNOW THAT THIS IS THE PRIMARY KEY
    protected $fillable = [
        'announcement_id',
        'club_id',
        'title',
        'description',
        'cover_image',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    public $timestamps = false;
}
