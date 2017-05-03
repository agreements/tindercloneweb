<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotificationsPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createNotificationsTable();
		$this->createNotificationSettingsTable();
	}

	public function uninstall()
	{
		
	}


	public function createPushNotificationsTable()
	{
		Schema::dropIfExists('push_notifications');

  		Schema::create('push_notifications', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('user_id');
	            $table->string('device_id', 255)->nullable();
	            $table->string('device_token', 255);
	            
	            $table->timestamps();
	            $table->softDeletes();
        });
	}



	public function createNotificationsTable () {

		Schema::dropIfExists('notifications');

  		Schema::create('notifications', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('from_user');
	            $table->bigInteger('to_user');
	            $table->string('type', 100);
	            $table->string('status', 100);
	            $table->bigInteger('entity_id');
	            $table->enum('notification_hook_type', ['left_menu', 'central']);
	            
	            $table->timestamps();
	            $table->softDeletes();
        });
	}

	public function createNotificationSettingsTable () {

		Schema::dropIfExists('notification_settings');

  		Schema::create('notification_settings', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('userid');
	            $table->string('type', 200);
	            $table->smallInteger('browser');
	            $table->smallInteger('email');
	            $table->timestamps();
	            $table->softDeletes();
        });
	}

}