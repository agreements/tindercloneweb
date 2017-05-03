<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Photo extends Model
{
     use SoftDeletes;
    
    protected $table = 'photos';
    protected $dates = ['deleted_at'];
    public $timestamps = true;

	protected $fillable = [
        'id',
        'userid', 
        'source_photo_id', 
        'photo_source', 
        'photo_url', 
        'created_at', 
        'updated_at', 
        'deleted_at'
    ];
	
	
	public static function boot()
    {
        parent::boot();

        static::saved(function($photo){
	        
	        $photos = User::find($photo->userid)->photos;
	       
            $photos = $photos->reject(function($item) use($photo){
                return $photo->id == $item->id;
            });
        
            $photos->push($photo);
        
            \Cache::put("user_{$photo->userid}_photos", serialize($photos), 10 * 60);            
            
        });


        static::deleting(function($photo){
            
            $photos = User::find($photo->userid)->photos;

            $photos = $photos->reject(function($item) use($photo){
                return $photo->id == $item->id;
            });

            \Cache::put("user_{$photo->userid}_photos", serialize($photos), 10 * 60);            

        });

    } 
	
 
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'userid');
    }

    public function photo_url()
    {
         return asset('uploads/others/original/'. $this->photo_url);
    }

    public function encounter_photo_url()
    {
        return asset('uploads/others/encounters/'. $this->photo_url);
    }

    public function thumbnail_photo_url() {
        return asset('uploads/others/thumbnails/'. $this->photo_url);
    }

    public function original_photo_url() {
        return asset('uploads/others/original/'. $this->photo_url);
    }

    public function other_photo_url() {
        return asset('uploads/others/'. $this->photo_url);
    }
}