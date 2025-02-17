<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorPreference extends Model
{
    protected $fillable = ['author_pref', 'users_id'];
}
