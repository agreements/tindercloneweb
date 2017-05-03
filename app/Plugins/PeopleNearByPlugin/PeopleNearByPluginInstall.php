<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PeopleNearByPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createRiseupTable();

		$this->createUserPreferencesTable();
	}

	public function uninstall()
	{
		
	}

	public function createRiseupTable()
	{
		Schema::dropIfExists('riseup');

  		Schema::create('riseup', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('userid');	            
	            $table->timestamps();	 
	            $table->softDeletes();           
        });
	}

	public function createUserPreferencesTable()
	{
		Schema::dropIfExists('user_preferences');

  		Schema::create('user_preferences', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('user_id');
	            $table->bigInteger('field_id');
	            $table->string('prefer_value',500);
	            $table->timestamps();	            
	            $table->softDeletes();
        });
	}
}