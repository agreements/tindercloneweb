<?php

namespace App\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class BlockUsers extends Model
{
    protected $table = 'blockusers';

    //for created_at and updated_at field
    public $timestamps = true;



    public function user()
    {
        return $this->belongsTo('App\Models\User','user2');
    }

}