<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdsPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createAdsActiveTable();
		$this->createAdsPluginTable();
	}

	public function uninstall()
	{
		
	}


	public function createAdsActiveTable () {

		Schema::dropIfExists('ads_active');

  		Schema::create('ads_active', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('top');
	            $table->bigInteger('leftsidebar');
	            $table->bigInteger('rightsidebar');
	            $table->bigInteger('bottom');
	            
	            $table->timestamps();
	            $table->softDeletes();
        });
	}


	public function createAdsPluginTable () {

		Schema::dropIfExists('ads_plugin');

  		Schema::create('ads_plugin', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('name', 500);
	            $table->longText('code');

	            $table->timestamps();
	            $table->softDeletes();
        });
	}

}