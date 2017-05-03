<?php

namespace App\Components;

/* This abstract class contains some abstarct functions which must be implemented by every Theme Class
	in side every theme folder
*/
abstract class PluginAbstract
	{
	//abstact methods
	abstract public function author(); //to retrive the author name
	abstract public function description(); // to retrive the description of the theme
	abstract public function version(); //to retrive the version of theme
	abstract public function website(); // to retrive the website name of the theme
	abstract public function hooks(); //this is action hoocks

	//optional methods
	public function routes(){}
	public function autoload(){
		return array();
	}
	public function isCore()
	{
		return false;
	}
}
