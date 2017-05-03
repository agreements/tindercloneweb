<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Photo;

class NudePhoto extends Model {

     use SoftDeletes;
  
    protected $table = 'nude_photo_lists';
    protected $dates = ['deleted_at'];
    public $timestamps = true;

    public function user () {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function photo () {
        $photo = Photo::withTrashed()->where('photo_url', $this->photo_name)->first();
        return $photo;
    }


    public function isPhotoExists()
    {
        $photo = $this->photo();
        return $photo ? true : false;
    }

    public function detected_at () {
        if($this->created_at == null)
            return null;
        
        return date('d.m.Y', strtotime($this->created_at));
    }


    public function status () {
        return trans('admin.nude_photo_'.$this->status);
    }
}