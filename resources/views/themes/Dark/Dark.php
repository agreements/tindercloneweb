<?php
namespace Dark;
use App\Components\ThemeInterface;
use App\Components\Theme;

class Dark implements ThemeInterface
{
	public function child()
	{
		return "DefaultTheme";
	}

	public function author()
	{
		return 'www.socailoxide.club';
	}

	public function description()
	{
		return 'This is child theme of parent DefaultTheme';
	}

	public function version()
	{
		return '1.0.0';
	}

	public function website()
	{
		return 'www.socailoxide.club';
	}

	public function hooks()
	{
		
		Theme::hook("styles",function(){
			$arr = array();
			array_push($arr, Theme::asset('css/black-theme.css'));
			array_push($arr, Theme::asset('css/black-theme-responsive.css'));
			
			return $arr;
		});
	}
}