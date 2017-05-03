<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//for soft delete 
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\UserFields;
use App\Models\FieldOptions;

use Illuminate\Database\Eloquent\Relations\HasMany;


class CustomFieldOptionsHasMany extends HasMany{
	/*
	 public function __construct(Builder $query, Model $parent, $foreignKey, $localKey)
    {
        $this->localKey = $localKey;
        $this->foreignKey = $foreignKey;
        $this->parent_model = $parent;
        parent::__construct($query, $parent);
    }
    
    
    
     public function getResults()
    {
	    //dd($this->localKey);
        app("App\Models\FieldOptions")->where("field_id", $this->parent_model->id);
    } */
    
	
}

class Fields extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fields';

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
    public function field_options()
    {
        return $this->hasFieldOptions('App\Models\FieldOptions','field_id');
        
        //return new CustomFieldOptionsHasMany($this->query, $this,'field_id');
        //return app("App\Models\FieldOptions")->where("field_id", $this->id);
    }  */
    
    public static $file_fields = array();
    
    public function __construct($attributes = array())  {
        parent::__construct($attributes); // Eloquent
        
        $file = config("fields");
        
        foreach($file as $k => $v){
	        
	        $u_v = unserialize($v);
	        self::$file_fields[$k] = $u_v;
	        
	       
        }
        
    }
    
    
    public function getByFieldId($id){
	    $f = self::$file_fields[$id];
	    $f = new Fields();
			    $f->id = $field->id;
		$f->name = $field->name;
		$f->code = $field->code;
		$f->section_id = $field->section_id;
		$f->type = $field->type;
		$f->on_registration = $field->on_registration;
		$f->on_search = $field->on_search;
		$f->on_search_type = $field->on_search_type;
		$f->unit = $field->unit;
	    
	    return $f;
    }
    
    
      public function getOnSearch(){
	    $fields = self::$file_fields;
	    
	    $filter = array();
	    
	    foreach($fields as $field){
		    
		    if($field->on_search == "yes"){
			    $f = new Fields();
			    $f->id = $field->id;
		$f->name = $field->name;
		$f->code = $field->code;
		$f->section_id = $field->section_id;
		$f->type = $field->type;
		$f->on_registration = $field->on_registration;
		$f->on_search = $field->on_search;
		$f->on_search_type = $field->on_search_type;
		$f->unit = $field->unit;
			    array_push($filter, $f);
		    }
	    }
	    
	    
	    return collect($filter);
    }
    
    public  function getBySectionId($section_id){
	    
	    $fields = self::$file_fields;
	    
	    $filter = array();
	    
	    foreach($fields as $field){
		    
		    if($field->section_id == $section_id){
			    $f = new Fields();
			    $f->id = $field->id;
		$f->name = $field->name;
		$f->code = $field->code;
		$f->section_id = $field->section_id;
		$f->type = $field->type;
		$f->on_registration = $field->on_registration;
		$f->on_search = $field->on_search;
		$f->on_search_type = $field->on_search_type;
		$f->unit = $field->unit;
			    array_push($filter, $f);
		    }
	    }
	    
	    
	    return collect($filter);
	    
	    
    }
    
    
    public function getGenderField(){
	    
	    $fields = self::$file_fields;
	    
	    $filter = array();
	    
	    foreach($fields as $field){
		    
		    if($field->code == "gender"){
			    $f = new Fields();
			    $f->id = $field->id;
		$f->name = $field->name;
		$f->code = $field->code;
		$f->section_id = $field->section_id;
		$f->type = $field->type;
		$f->on_registration = $field->on_registration;
		$f->on_search = $field->on_search;
		$f->on_search_type = $field->on_search_type;
		$f->unit = $field->unit;
			    return $f;
		    }
	    }
	    
	   
    }
    
    
    public function getGenderFields(){
	    
	    $fields = self::$file_fields;
	    
	    $filter = array();
	    
	    foreach($fields as $field){
		    
		    if($field->code == "gender"){
			    $f = new Fields();
			    $f->id = $field->id;
		$f->name = $field->name;
		$f->code = $field->code;
		$f->section_id = $field->section_id;
		$f->type = $field->type;
		$f->on_registration = $field->on_registration;
		$f->on_search = $field->on_search;
		$f->on_search_type = $field->on_search_type;
		$f->unit = $field->unit;
			    array_push($filter, $f);
		    }
	    }
	    
	    
	    return collect($filter);
    }
    
    
    
    
    public function getFieldOptionsAttribute(){
	   return  app("App\Models\FieldOptions")->where("field_id", $this->id);
    } 
    
    
    
    
    public function hasFieldOptions($related, $foreignKey = null, $localKey = null)
    {
        $foreignKey = $foreignKey ?: $this->getForeignKey();
        $instance = new $related;
        $localKey = $localKey ?: $this->getKeyName();
        return new CustomFieldOptionsHasMany($instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $localKey);
    }

    public function option_sort()
    {
        $arr = [];
        $options = $this->hasMany('App\Models\FieldOptions','field_id');
        foreach($options as $option)
        {
            array_push($arr, $option->name);
        }
        sort($arr);
        return $arr;
    }

    public function user_field($userId)
    {

        switch ($this->type) {

            case 'dropdown':
                return $this->getUserSelectedDropdownValue($userId);
                break;

            case 'checkbox':
                return $this->getUserSelectedCheckboxValues($userId);
                break;


            case 'text':
            case 'textarea':
                return $this->getUserSelectedTextualValue($userId);
                break;

            default:
                return '';
                break;
            
        }

    }

    protected function getUserSelectedCheckboxValues($userId)
    {
        $valueCodes = [];

        $userFields = UserFields::where('user_id', $userId)->where('field_id', $this->id)->get();

        foreach($userFields as $userField) {
            $option = FieldOptions::where('field_id', $this->id)->where('id', $userField->value)->first();
            array_push($valueCodes, $option->code);
        }

        return $valueCodes;
    }


    protected function getUserSelectedTextualValue($userId)
    {
        $userField = UserFields::where('user_id', $userId)->where('field_id', $this->id)->first();
        return ($userField) ? $userField->value : "";
    }

    protected function getUserSelectedDropdownValue($userId) 
    {
        $userField = UserFields::where('user_id', $userId)->where('field_id', $this->id)->first();
        
        if($userField && ( $option = FieldOptions::where('field_id', $this->id)->where('id', $userField->value)->first() ) ) {
            
            return $option->code;
        } 
        
        return '';
    }
    
    
     public static function syncWithConfig(){
	      
	    $fields = \App\Models\Fields::all();
	$arr = array();
	foreach($fields as $field){
		
		$f = new \stdClass;
		$f->id = $field->id;
		$f->name = $field->name;
		$f->code = $field->code;
		$f->section_id = $field->section_id;
		$f->type = $field->type;
		$f->on_registration = $field->on_registration;
		$f->on_search = $field->on_search;
		$f->on_search_type = $field->on_search_type;
		$f->unit = $field->unit;
		$arr[$field->id] = serialize($f);
		
	}
	
	$arrayString = var_export($arr, true);
        $arrayString = "<?php return \n {$arrayString};"; 
        file_put_contents(config_path("fields.php"), $arrayString, LOCK_EX);
        
        
    }



    public static function boot()
    {
        parent::boot();

        static::saved(function($field){
	        
	        self::syncWithConfig();   
            
        });

        static::deleted(function($field){
	        
	        self::syncWithConfig();   
            
        });

    } 



}
