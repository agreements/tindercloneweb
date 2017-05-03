<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model {
    
    use SoftDeletes;

    protected $table = 'msg_chat';
    protected $dates = ['deleted_at'];

    //for created_at and updated_at field
    public $timestamps = true;

}
