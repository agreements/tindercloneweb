<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SpotlightPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createSpotlightTable();	
	}

	public function uninstall()
	{
		
	}

	public function createSpotlightTable()
	{
		Schema::dropIfExists('spotlight');

  		Schema::create('spotlight', function (Blueprint $table) {
	    		
	    		$table->bigIncrements('id');
	            $table->bigInteger('userid');	            
	            $table->timestamps();	
	            $table->softDeletes();    
 	            
        });
	}
}