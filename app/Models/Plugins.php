<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Plugins extends Model
{
    protected $table = 'plugins';

    //for created_at and updated_at field
    public $timestamps = true;
}