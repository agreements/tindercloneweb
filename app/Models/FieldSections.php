<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//for soft delete 
use Illuminate\Database\Eloquent\SoftDeletes;

class FieldSections extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'field_sections';

    //for softdelete
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = ['password', 'remember_token'];

    //for created_at and updated_at field
    public $timestamps = true;
/*
    public function fields()
    {
        return $this->hasMany('App\Models\Fields','section_id');
    } */
    
    public function getFieldsAttribute(){
	    
	    return app("App\Models\Fields")->getBySectionId($this->id);
    }
    
    
      public static function syncWithConfig(){
	      
	    $field_sections = \App\Models\FieldSections::all();
	$arr = array();
	foreach($field_sections as $field){
		
		$f = new \stdClass;
		$f->name = $field->name;
		$f->code = $field->code;
		// $f->name = $field->name;
		// $f->code = $field->code;
		$arr[$field->id] = serialize($f);
		
	}
	
	$arrayString = var_export($arr, true);
        $arrayString = "<?php return \n {$arrayString};"; 
        file_put_contents(config_path("fieldsections.php"), $arrayString, LOCK_EX);
        
        
    }

    public static function boot()
    {
        parent::boot();

        static::saved(function($fieldSection){
            
            self::syncWithConfig();   
            
        });

        static::deleted(function($fieldSection){
            
            self::syncWithConfig();   
            
        });

    } 

}
