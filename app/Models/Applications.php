<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applications extends Model
{
    use HasFactory;

    protected $table = 'tbl_applications_aspirants'; //THIS LINE LETS LARAVEL KNOW THAT THE USERS TABLE IS NAMES AS tbl_users
    public $incrementing = false;
    protected $primaryKey = 'application_id'; // THIS LINE LETS LARAVEL KNOW THAT THIS IS THE PRIMARY KEY
    protected $fillable = [
        'application_id',
        'firstname',
        'middlename',
        'lastname',
        'email',
        'number',
        'application_file',
        'club_id',
    ];

    public $timestamps = false;
}
