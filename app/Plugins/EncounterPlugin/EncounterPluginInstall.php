<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EncounterPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createEncounterTable();
		$this->createMatchesTable();
	}

	public function uninstall()
	{
		
	}


	public function createEncounterTable()
	{
		Schema::dropIfExists('encounter');

  		Schema::create('encounter', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('user1');
	            $table->bigInteger('user2');
	            $table->smallInteger('likes');
	            $table->timestamps();
	            $table->softDeletes();
        });
	}

	public function createMatchesTable()
	{
		Schema::dropIfExists('matches');

  		Schema::create('matches', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('user1');
	            $table->bigInteger('user2');
	            $table->timestamps();
	            $table->softDeletes();
        });
	}

}