<?php

namespace App\Components;

abstract class PluginInstall 
{
	abstract public function install(); // this function must be defined every class that extends this abstract class

	public function uninstall(){} // this function is not compulsory 
}