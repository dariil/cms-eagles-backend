<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Official_Archive extends Model
{
    use HasFactory;

    protected $table = 'tbl_archived_officials'; //THIS LINE LETS LARAVEL KNOW THAT THE USERS TABLE IS NAMES AS tbl_users
    public $incrementing = false;
    protected $primaryKey = 'official_id'; // THIS LINE LETS LARAVEL KNOW THAT THIS IS THE PRIMARY KEY
    protected $fillable = [
        'official_id',
        'club_id',
        'official_name',
        'official_position',
        'official_image',
        'official_description',
    ];

    public $timestamps = false;
}
