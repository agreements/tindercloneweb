<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Admin extends Model
{
    
    protected $table = 'admin';

    //for created_at and updated_at field
    public $timestamps = true;
}