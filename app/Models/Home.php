<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    use HasFactory;

    protected $table = 'tbl_home'; //THIS LINE LETS LARAVEL KNOW THAT THE USERS TABLE IS NAMES AS tbl_users
    public $incrementing = false;
    protected $primaryKey = 'home_id'; // THIS LINE LETS LARAVEL KNOW THAT THIS IS THE PRIMARY KEY
    protected $fillable = [
        'home_id',
        'hero_title',
        'hero_tagline',
        'hero_video',
        'club_id',
        'logo',
        'description',
        'updated_by',
    ];

    public $timestamps = false;
}
