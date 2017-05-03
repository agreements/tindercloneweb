<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use \Config;

//for soft delete 
use Illuminate\Database\Eloquent\SoftDeletes;

class Settings extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';

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

    public static function get($key)
    {
	    return config("settings.".$key);       
    }

    public static function set($key, $value)
    {
        $setting = self::where('admin_key','=',$key)->first();
        
        if($setting) {
        
            $setting->value = $value;
        
        }else{

            $setting = new self;
            $setting->admin_key = $key;
            $setting->value = $value;
        }
        
        $setting->save();
        
        config(["settings.{$key}" => "$value"]);
        self::syncSettingsWithConfig(config("settings"));
        
    }

    protected static function settingsConfigPath() 
    {
        return config_path('settings.php');
    }

    public static function syncSettingsWithConfig($configSettinsArray)
    {
        $arrayString = var_export($configSettinsArray, true);
        $arrayString = "<?php return \n {$arrayString};"; 
        file_put_contents(self::settingsConfigPath(), $arrayString, LOCK_EX);
    }


    public static function syncSettingsWithDatabase()
    {
        $finalArray = [];
        self::select(['admin_key', 'value'])->chunk(100, function($settings) use(&$finalArray){

            $settingsArray = $settings->toArray();
            self::flatArray($settingsArray, $finalArray);
           
        });

        self::syncSettingsWithConfig($finalArray);
        
    }

    protected static function flatArray($array, &$finalArray = [])
    {
        array_map(function($eachElement) use(&$finalArray){
            $finalArray[$eachElement['admin_key']] = $eachElement['value'];
        }, $array);

        return $finalArray;
    }


    //_get method only useing for email settings in AppServiceProvider.

    public static function _get($key) 
    {
	   return config("settings.".$key);
    }
    
   
}