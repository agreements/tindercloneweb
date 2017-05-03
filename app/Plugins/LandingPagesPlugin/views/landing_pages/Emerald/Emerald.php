<?php

use App\Plugins\LandingPagesPlugin\LandingPageInterface;

class Emerald implements LandingPageInterface {

	public function languageFile() {
		return "LandingPagesPlugin";
	}

	public function contentSections() {
		return [
			"first_screen_main_heading_top",'made_with', 'remember_me',
			"first_screen_main_heading",
			"first_screen_main_heading_sub",
			"signup_top", 'signup_bottom', 'privacy_policy', 'cookie_policy', 'terms_and_conditions', 'about_us',
			'testimonials_heading',

			"testimonial_one_name",
			"testimonial_one_country",
			"testimonial_one_age",
			"testimonial_one_post_and_company",
			"testimonial_one_description",

			"testimonial_two_name",
			"testimonial_two_country",
			"testimonial_two_age",
			"testimonial_two_post_and_company",
			"testimonial_two_description",

			"testimonial_three_name",
			"testimonial_three_country",
			"testimonial_three_age",
			"testimonial_three_post_and_company",
			"testimonial_three_description",

			"testimonial_four_name",
			"testimonial_four_country",
			"testimonial_four_age",
			"testimonial_four_post_and_company",
			"testimonial_four_description",

			"testimonial_five_name",
			"testimonial_five_country",
			"testimonial_five_age",
			"testimonial_five_post_and_company",
			"testimonial_five_description",

			"testimonial_six_name",
			"testimonial_six_country",
			"testimonial_six_age",
			"testimonial_six_post_and_company",
			"testimonial_six_description",

			"testimonial_seven_name",
			"testimonial_seven_country",
			"testimonial_seven_age",
			"testimonial_seven_post_and_company",
			"testimonial_seven_description",

			"testimonial_eight_name",
			"testimonial_eight_country",
			"testimonial_eight_age",
			"testimonial_eight_post_and_company",
			"testimonial_eight_description",

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
			"testimonial_one_image" => [
				'url' => "http://www.psicologiamsn.com/wp-content/uploads/2015/10/beautiful_girl_by_matsuyatokagura142-d7ef8t8.jpg"
			], 
			"testimonial_two_image" => [
				'url' => "http://www.psicologiamsn.com/wp-content/uploads/2015/10/beautiful_girl_by_matsuyatokagura142-d7ef8t8.jpg"
			], 
			"testimonial_three_image" => [
				'url' => "http://www.psicologiamsn.com/wp-content/uploads/2015/10/beautiful_girl_by_matsuyatokagura142-d7ef8t8.jpg"
			], 
			"testimonial_four_image" => [
				'url' => "http://www.psicologiamsn.com/wp-content/uploads/2015/10/beautiful_girl_by_matsuyatokagura142-d7ef8t8.jpg"
			], 
			"testimonial_five_image" => [
				'url' => "http://www.psicologiamsn.com/wp-content/uploads/2015/10/beautiful_girl_by_matsuyatokagura142-d7ef8t8.jpg"
			], 
			"testimonial_six_image" => [
				'url' => "http://www.psicologiamsn.com/wp-content/uploads/2015/10/beautiful_girl_by_matsuyatokagura142-d7ef8t8.jpg"
			], 
			"testimonial_seven_image" => [
				'url' => "http://www.psicologiamsn.com/wp-content/uploads/2015/10/beautiful_girl_by_matsuyatokagura142-d7ef8t8.jpg"
			], 
			"testimonial_eight_image" => [
				'url' => "http://www.psicologiamsn.com/wp-content/uploads/2015/10/beautiful_girl_by_matsuyatokagura142-d7ef8t8.jpg"
			], 

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
			"about_us" => [
				"url" => url("about-us"),
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