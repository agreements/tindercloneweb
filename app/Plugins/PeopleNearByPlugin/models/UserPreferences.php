<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Fields;

class UserPreferences extends Model {
    use SoftDeletes;
    
    protected $table = 'user_preferences';
    protected $dates = ['deleted_at'];
    public $timestamps = true;

    public function getFieldType() {
        //$field = Fields::where('id',$this->field_id)->first();
        $field = app("App\Models\Fields")->getByFieldId($this->field_id);
        return $field->type;
    }

    public function field () {
    	return $this->belongsTo('App\Models\Fields', 'field_id');
    }
}
