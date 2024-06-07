<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    use HasFactory;

    protected $table = 'tbl_home'; //THIS LINE LETS LARAVEL KNOW THAT THE USERS TABLE IS NAMES AS tbl_users
    protected $primaryKey = 'home_id'; // THIS LINE LETS LARAVEL KNOW THAT THIS IS THE PRIMARY KEY
    protected $fillable = [
        'club_id',
        'logo',
        'description',
        'updated_by',
    ];

    public $timestamps = false;
}
