<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;


class Role extends Model
{
    use HasFactory;

    use Authorizable;

    protected $fillable = ['name','guard_name','description'];
    
}
