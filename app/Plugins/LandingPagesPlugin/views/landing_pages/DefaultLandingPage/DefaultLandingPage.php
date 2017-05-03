<?php

use App\Plugins\LandingPagesPlugin\LandingPageInterface;

class DefaultLandingPage implements LandingPageInterface {

	public function languageFile() {
		return "LandingPagesPlugin";
	}

	public function contentSections() {
		return [
			"first_screen_main_heading", 'remember_me',
			"first_screen_main_heading_sub", 'about_us_before_text',
			"signup_top", 'signup_bottom', 'privacy_policy', 'about_us', 'terms_and_conditions', "top_signin_text","top_signup_text",
			
			"reset_password_text", "forgot_password_link_text", "remember_password_link_text", "reset_password_button_text" 
		];
	}

	public function imageSections() {
		return [			
		];
	}

	public function linkSections() {
		return [
			"privacy_policy" => [
				'url' => url('privacy-policy')
			],
			"terms_and_conditions" => [
				"url" => url("terms-and-conditions"),
			],
			"about_us" => [
				"url" => url("about-us"),
			],
		];
	}
}