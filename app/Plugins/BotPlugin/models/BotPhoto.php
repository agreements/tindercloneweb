<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BotPhoto extends Model
{
    use SoftDeletes;
    protected $table    = 'bot_photos';
    protected $dates    = ['deleted_at'];
    public $timestamps  = true;
    protected $fillable = [
        'bot_id', 'photo_name', 'created_at', 'updated_at', 'deleted_at'
    ];

}
