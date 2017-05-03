<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatContact extends Model {
    use SoftDeletes;
    
    protected $table = 'contacts';
    protected $dates = ['deleted_at'];
    public $timestamps = true;

    public function chat_msg()
    {
        return $this->hasMany('App\Models\Message','contact_id');
    }

    public function last_user()
    {
        return $this->belongsTo('App\Models\User','user2');
    }
}
