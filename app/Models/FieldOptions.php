<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\UserFields;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;




class FieldOptions extends Model
{

    use SoftDeletes;
    protected $table = 'field_options';
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    
    public static $from_file_ids = array();
    public static $from_file_codes = array();
   

	public function __construct($attributes = array())  {
        parent::__construct($attributes); // Eloquent
        
        $file = config("fieldoptions");
        
        foreach($file as $k => $v){
	        
	        $u_v = unserialize($v);
	        self::$from_file_ids[$k] = $u_v;
	        self::$from_file_codes[$u_v->code] = $u_v;
	       
        }
        
    }

    public function hasUserOption($userId)
    {
        $userField = UserFields::where('user_id', $userId)
                            ->where('value', $this->id)
                            ->where('field_id', $this->field_id)
                            ->first();

        return ($userField) ? true : false;
    }
    
    
    public function getFromFileIds(){
	    
	    return self::$from_file_ids;
    }
    
    public function getFromFileCodes(){
	    
	    return self::$from_file_codes;
    }
    
    public static function where($key, $value){
	    
	   
	    if($key == "id"){
		    $obj = self::$from_file_ids[$value];
		    $t = new FieldOptions();
		    $t->id = $value;
		    $t->name = $obj->name;
		    $t->field_id = $obj->field_id;
		    $t->code = $obj->code;
		    return collect(array($t));
	    }
	    
	    if($key == "code"){
		    $obj = self::$from_file_codes[$value];
		    $t = new FieldOptions();
		    $t->code = $value;
		    $t->name = $obj->name;
		    $t->id = $obj->id;
		    $t->field_id = $obj->field_id;
		    return collect(array($t));
	    }
	    
	     if($key == "field_id"){
		     // Illuminate\Database\Eloquent\Relations\Relation
		   return self::getByFieldId($value);
	    }
	    
    } 
    
    
    public static function getByFieldId($field_id){
	    
	    $fields = self::$from_file_ids;
	    
	    $filter = array();
	    
	    foreach($fields as $field){
		    
		    if($field->field_id == $field_id){
			    $f = new FieldOptions();
			    $f->id = "$field->id";
			    $f->field_id = $field_id;
			    $f->name = $field->name;
			    $f->code = $field->code;
			    array_push($filter, $f);
		    }
	    }
	    
	    
	    return collect($filter);
	    
	    
    }
    
    
    public static function syncWithConfig(){
	    $field_options = \App\Models\FieldOptions::all();
	$arr = array();
	foreach($field_options as $field){
		
		$f = new \stdClass;
		$f->id = $field->id;
		$f->field_id = $field->field_id;
		$f->name = $field->name;
		$f->code = $field->code;
		$arr[$field->id] = serialize($f);
		
	}
	
	$arrayString = var_export($arr, true);
        $arrayString = "<?php return \n {$arrayString};"; 
        file_put_contents(config_path("fieldoptions.php"), $arrayString, LOCK_EX);
    }


    public static function boot()
    {
        parent::boot();

        static::saved(function($fieldOption){
	        
	        self::syncWithConfig();   
            
        });

        static::deleted(function($fieldOption){
	        
	        self::syncWithConfig();   
            
        });

    } 
    
    /*
      public function __call($method, $parameters)
    {
	    dd($method);
        if($method == "where"){
	        dd("blah");
	        return call_user_func_array([$this, $method], $parameters);
        }
        else{ 
        parent::__call($method, $parameters);
        }
    } */
    
}
