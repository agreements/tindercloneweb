<?php

use App\Components\PluginInstall;
use App\Repositories\Admin\UtilityRepository;

class LandingPagesPluginInstall extends PluginInstall
{
	public function install()
	{
		UtilityRepository::set_setting('custom_landing_page', 'DefaultLandingPage');
	}

	public function uninstall()
	{
		
	}

}