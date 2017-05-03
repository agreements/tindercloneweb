<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatSocketMap extends Model {

    use SoftDeletes;
    protected $table = 'websocket_chat_maps';
    protected $dates = [
        'deleted_at'
    ];

    protected $hidden = [
        'socket_id'
    ];

    //for created_at and updated_at field
    public $timestamps = true;

}
