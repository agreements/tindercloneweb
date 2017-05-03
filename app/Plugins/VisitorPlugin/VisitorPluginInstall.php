<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VisitorPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createVisitorsTable();	
	}

	public function uninstall()
	{
		
	}

	public function createVisitorsTable()
	{
		Schema::dropIfExists('visitors');

  		Schema::create('visitors', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('user1');
	            $table->bigInteger('user2');	
	            $table->string('status', 50);           
	            $table->timestamps();         
	            $table->softDeletes();
        });
	}

}