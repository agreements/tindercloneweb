<?php

namespace App\Components;
/* This interface contains some abstarct functions which must be implemented by every Theme Class
	in side every theme folder
*/
interface ThemeInterface
{
	//abstact methods
	public function author(); //to retrive the author name
	public function description(); // to retrive the description of the theme
	public function version(); //to retrive the version of theme
	public function website(); // to retrive the website name of the theme
}