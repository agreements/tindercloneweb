<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Pages;
use Illuminate\Support\Facades\DB;

class PagesPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createPagesTable();
		$this->insertSlugCol();
		DB::table('pages')->insert(array('title' => 'Privacy Policy', 'route' => 'privacy-policy'));
		DB::table('pages')->insert(array('title' => 'About Us', 'route' => 'about-us'));
		DB::table('pages')->insert(array('title' => 'Terms and Conditions', 'route' => 'terms-and-conditions'));
		DB::table('pages')->insert(array('title' => 'Cookie Policy', 'route' => 'cookie-policy'));
		
	}

	public function uninstall()
	{
		
	}

	public function insertSlugCol()
	{
		Schema::table('interests', function ($table) {
	    	$table->string('slug',200)->after('interest');
		});
	}

	public function createPagesTable() {

		Schema::dropIfExists('pages');

  		Schema::create('pages', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('title', 500);
	            $table->longText('body');
	            $table->string('route', 500);
	            $table->string('layout', 500);
	            $table->timestamps();
	            $table->softDeletes();
        });
	}
}