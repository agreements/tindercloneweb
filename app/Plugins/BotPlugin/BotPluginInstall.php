<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BotPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createBotTable();
		$this->createBotFieldsTable();
		$this->createBotPhotos();
	}

	public function uninstall()
	{
		
	}

	public function createBotPhotos()
	{
		Schema::dropIfExists('bot_photos');
		Schema::create('bot_photos', function(Blueprint $table){
			$table->bigIncrements('id');
			$table->bigInteger('bot_id');
			$table->string('photo_name', 500);
			$table->timestamps();
	        $table->softDeletes();
		});
	}


	public function createBotTable () {

		Schema::dropIfExists('bot');

  		Schema::create('bot', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');

	            $table->string('name', 100);
	            $table->string('password', 1000);
	            $table->string('gender', 10);
	            $table->string('status', 100);
	            $table->string('isactive', 100);
	            $table->string('hereto', 100);
	            $table->string('profile_pic', 200);
	            $table->string('height', 100);
	            $table->string('weight', 100);
	            $table->string('aboutme', 500);
	            $table->string('eyecolor', 100);
	            $table->string('bodytype', 100);
	            $table->string('haircolor', 100);
	            $table->string('living', 100);
	            $table->string('children', 100);
	            $table->string('smoking', 100);
	            $table->string('drinking', 100);
	            $table->string('relationship', 100);
	            $table->string('sexuality', 100);
	            $table->date('dob');
	            $table->date('joining');
	            
	            $table->timestamps();
	            $table->softDeletes();
        });
	}

	public function createBotFieldsTable()
	{
		Schema::dropIfExists('bot_fields');

  		Schema::create('bot_fields', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('bot_id');
	            $table->text('value');
	            $table->bigInteger('field_id');
	            $table->timestamps();	            
	            $table->softDeletes();
        });
	}


}