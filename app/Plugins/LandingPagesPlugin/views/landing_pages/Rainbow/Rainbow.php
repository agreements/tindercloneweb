<?php

use App\Plugins\LandingPagesPlugin\LandingPageInterface;

class Rainbow implements LandingPageInterface {

	public function languageFile() {
		return "LandingPagesPlugin";
	}

	public function contentSections() {
		return [			
			"first_screen_main_heading_sub",
			"signup_top", 'signup_bottom', 'privacy_policy', 'cookie_policy', 'terms_and_conditions', 'remember_me',
			'help',
			"reset_password_text", "forgot_password_link_text", "remember_password_link_text", "reset_password_button_text",			
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
			"cookie_policy" => [
				"url" => url("cookie-policy"),
			],
			"about_us" => [
				"url" => url("about-us"),
			],
			"help" => [
				"url" => url(""),
			], 
			"terms_and_conditions" => [
				"url" => url("pages/terms-and-conditions"),
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