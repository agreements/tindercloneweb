<?php

use App\Plugins\LandingPagesPlugin\LandingPageInterface;

class MuslimDate implements LandingPageInterface {

	public function languageFile() {
		return "LandingPagesPlugin";
	}

	public function contentSections() {
		return [			
			"first_screen_main_heading_bold_text",'welcome_to', 'remember_me',
			"first_screen_main_heading_sub",
			"signup_top", 'signup_bottom', 'privacy_policy', 'cookie_policy', 'terms_and_conditions',
			'testimonials_heading', 'testimonials_see_all_buttom_text',

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

			"social_oxide_link_text", "twitter_link_text", "facebook_link_text", "google_plus_link_text",
			
			"reset_password_text", "forgot_password_link_text", "remember_password_link_text", "reset_password_button_text",

			"middle_content_first_heading",
			"middle_content_first_description",
			"middle_content_second_heading",
			"middle_content_second_description",
			"middle_content_third_heading",
			"middle_content_third_description",
			"middle_content_button_top_text",
			"middle_content_button_text",
		];
	}

	public function imageSections() {
		return [
			"testimonial_background_image" => [
				'url' => url("plugins/LandingPagesPlugin/MuslimDate/images/bottom-img1.png")
			],
			"testimonial_one_image" => [
				'url' => "http://freewallpaper-background.com/hd/wp-content/uploads/2015/11/Beautiful-Girl-Wallpaper-HD-Download.jpg"
			], 
			"testimonial_two_image" => [
				'url' => "http://hdwallpaperbackgrounds.net/wp-content/uploads/2015/07/Beautiful-Girls-HD-Wallpapers-7.jpg"
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
			"middle_content_button_url" => [
				'url' => url('')
			], 
		];
	}
}