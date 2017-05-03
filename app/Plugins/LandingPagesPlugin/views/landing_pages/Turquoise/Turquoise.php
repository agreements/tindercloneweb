<?php

use App\Plugins\LandingPagesPlugin\LandingPageInterface;

class Turquoise implements LandingPageInterface {

	public function languageFile() {
		return "LandingPagesPlugin";
	}

	public function contentSections() {
		return [
			"first_screen_main_heading_top",'made_with', 'remember_me',
			"first_screen_main_heading",
			"first_screen_main_heading_sub",
			"signup_top", 'signup_bottom', 'privacy_policy', 'cookie_policy', 'terms_and_conditions', 'about_us',

			"middle_screen_heading", "middle_screen_heading_sub",
			"bottom_up_screen_left_heading",
			"bottom_up_screen_left_sub_heading",
			"bottom_up_screen_middle_heading",
			"bottom_up_screen_middle_sub_heading",
			"bottom_up_screen_right_heading",
			"bottom_up_screen_right_sub_heading",

			"social_oxide_link_text", "twitter_link_text", "facebook_link_text", "google_plus_link_text",
			
			"reset_password_text", "forgot_password_link_text", "remember_password_link_text", "reset_password_button_text" 
		];
	}

	public function imageSections() {
		return [
			"middle_screen_background_image" => [
				'url' => url("plugins/LandingPagesPlugin/Emerald/images/bg2.png")
			],
			"bottom_up_screen_left_image" => [
				'url' => url("plugins/LandingPagesPlugin/Emerald/images/free_icon.png")
			],
			"bottom_up_screen_middle_image" => [
				'url' => url("plugins/LandingPagesPlugin/Emerald/images/match_icon.png")
			],
			"bottom_up_screen_right_image" => [
				'url' => url("plugins/LandingPagesPlugin/Emerald/images/local_icon.png")
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
			]
		];
	}
}