<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WebsocketChatPluginInstall extends PluginInstall {

	public function install() {

		$this->createChatSocketMapsTable();
		$this->createMessageChatTable();
		$this->createContactsTable();
	}

	public function uninstall() {}

	public function createChatSocketMapsTable() {
		Schema::dropIfExists('websocket_chat_maps');

  		Schema::create('websocket_chat_maps', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('user_id', 255);
	            $table->string('socket_id', 255);
	            $table->string('offline_timer_id', 255)->nullable();
	            $table->enum('socket_map_for', ['web', 'mobile'])->default('web');
	            $table->timestamps();
	            $table->softDeletes();
        });
	}


	public function createMessageChatTable()
	{
		Schema::dropIfExists('msg_chat');

  		Schema::create('msg_chat', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('from_user');
	            $table->bigInteger('to_user');
	            $table->bigInteger('contact_id');
	            $table->longText('text');
	            $table->bigInteger('type');
	            $table->longText('meta');
	            $table->string('status', 1000);

	            $table->timestamps();
	            $table->softDeletes();
        });
	}

	public function createContactsTable () {

		Schema::dropIfExists('contacts');

  		Schema::create('contacts', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('user1');
	            $table->bigInteger('user2');
	            $table->string('source', 100);
	            $table->timestamps();
	            $table->softDeletes();
        });
	}

}
