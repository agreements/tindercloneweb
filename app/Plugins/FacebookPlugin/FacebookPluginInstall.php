<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FacebookPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createFacebookFriendsTable();
	}

	public function uninstall()
	{
		
	}

	public function createFacebookFriendsTable()
	{
		Schema::dropIfExists('facebook_friends');

  		Schema::create('facebook_friends', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('user1');
	            $table->bigInteger('user2');
	            $table->timestamps();
	            $table->softDeletes();
        });
	}

}