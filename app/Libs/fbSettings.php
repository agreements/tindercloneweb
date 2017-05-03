<?php 
namespace App\libs;
//use DB;
use App\Settings;
class fbSettings{
	
	public function settings() {
	//$fbId = DB::select('select * from settings where admin_key = :id', ['id' => 'fb_appId']);
	//$fbKey = DB::select('select * from settings where admin_key = :id', ['id' => 'fb_secretkey']);
	//return array('fbId'=>$fbId,'fbKey'=>$fbKey);
	$fbId = Settings::where('admin_key','=','fb_appId')->first();
	$fbKey = Settings::where('admin_key','=','fb_secretkey')->first();
	return array('fbId'=>$fbId->value,'fbKey'=>$fbKey->value);	
	}	
}
?>
