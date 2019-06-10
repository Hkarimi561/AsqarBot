<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupUsers extends Model
{
    protected $fillable=[
        'username','user_id','group','group_id','name'
    ];
}
