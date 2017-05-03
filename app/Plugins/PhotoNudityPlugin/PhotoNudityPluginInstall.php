<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PhotoNudityPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createNudePhotoTable();
		$this->addNudityAndNudityCheckedColumn();
	}

	public function uninstall()
	{
		
	}

	public function createNudePhotoTable()
	{
		Schema::dropIfExists('nude_photo_lists');

  		Schema::create('nude_photo_lists', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('photo_name', 255);
	            $table->bigInteger('user_id');
	            $table->enum('status', ['unseen', 'seen', 'deleted'])->default('unseen');
	            $table->timestamps();	            
	            $table->softDeletes();
        });
	}

	public function addNudityAndNudityCheckedColumn()
	{
		Schema::table('photos', function($table){
			$table->integer('nudity')->default(0);
			$table->integer('is_checked')->default(0);
		});
	}
}