<?php

use App\Plugins\LandingPagesPlugin\LandingPageInterface;

class Trendy implements LandingPageInterface {

	public function languageFile() {
		return "LandingPagesPlugin";
	}

	public function contentSections() {
		return [			
			"first_screen_main_heading_top",'first_screen_main_heading','made_with', 'remember_me',
			"signup_top", 'signup_bottom', 'privacy_policy', 'cookie_policy', 'terms_and_conditions', 'top_signup_text',
			'help', 'app_available_market_place_heading','app_available_market_place_heading_sub', 'top_signin_text',
			"reset_password_text", "forgot_password_link_text", "remember_password_link_text", "reset_password_button_text",	
			"middle_content_first_heading",
			"middle_content_first_description",
			"middle_content_second_heading",
			"middle_content_second_description",
			"middle_content_third_heading",
			"middle_content_third_description",		
		];
	}

	public function imageSections() {
		return [
			"app_available_market_place_image" => [
				'url' => url("plugins/LandingPagesPlugin/GayDate/images/devices.png")
			],
		];
	}

	public function linkSections() {
		return [
			"privacy_policy" => [
				'url' => url('privacy-policy')
			], 
			"cookie_policy" => [
				"url" => url("cookie-policy"),
			],
			
			"terms_and_conditions" => [
				"url" => url("terms-and-conditions"),
			],
			"facebook" => [
				"url" => url(""),
			]
			,"google_plus" => [
				"url" => url(""),
			],
			"twitter" => [
				"url" => url(""),
			],
			"socialoxide" => [
				"url" => url(""),
			],
		];
	}
}