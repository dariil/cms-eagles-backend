<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account_Archive extends Model
{
    use HasFactory;

    protected $table = 'tbl_archived_accounts'; //THIS LINE LETS LARAVEL KNOW THAT THE USERS TABLE IS NAMES AS tbl_users
    protected $primaryKey = 'user_id'; // THIS LINE LETS LARAVEL KNOW THAT THIS IS THE PRIMARY KEY
    protected $fillable = [
        'user_id',
        'club_id',
        'first_name',
        'middle_name',
        'last_name',
        'number',
        'email',
        'password',
        'access_level',
        'date_created',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id', 'club_id');
    }

    public $timestamps = false;
}
