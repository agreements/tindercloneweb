<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bot extends Model
{
     use SoftDeletes;
  
    protected $table = 'bot';

    //for softdelete
    protected $dates = ['deleted_at'];

    public $timestamps = true;
 	
    public function photos()
    {
        return $this->hasMany('App\Models\BotPhoto', 'bot_id');
    }

 	 public function age() {
        
        return date('d.m.Y', strtotime($this->dob));
        
    }

    public function joining () {

         return date('d.m.Y', strtotime($this->joining));
    }



    public function profile_pic_url()
    {
        return asset('uploads/others/original/'. $this->profile_pic);
    }

    public function thumbnail_pic_url()
    {
        return asset('uploads/others/thumbnails/'. $this->profile_pic);
    }

    public function status () {

        if ($this->isactive == 'true') {

        	return 'active';
        	
        } else {

        	return 'inactive';
        }
    }

}