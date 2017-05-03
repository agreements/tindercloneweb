<?php
namespace DefaultTheme;
use App\Components\ThemeInterface;
use App\Components\Theme;
use App\Repositories\Admin\UtilityRepository;

class DefaultTheme implements ThemeInterface
{
	public function author()
	{
		return 'www.socailoxide.club';
	}

	public function description()
	{
		return 'This is the parent DefaultTheme';
	}

	public function version()
	{
		return '1.0.0';
	}

	public function website()
	{
		return 'www.socailoxide.club';
	}

	public function hooks(){
    

		Theme::render_modifier("main_menu", function($data){

			usort($data, function($a ,$b){

				if ($a['priority'] == $b['priority']) {
			        return 0;
			    }
			    return ($a['priority']  < $b['priority'] ) ? -1 : 1;
			});

			$pre_content = '<li class=""><a href="{{url}}"><i class="{{class}}">{{symname}}</i>{{title}}</a><span class="red span_red_margin notification-{{notif_type}}">{{notif_count}}</span></li>';

			$content = '';

			foreach ($data as $link) {

				$temp_content = '';

				$temp_content = str_replace("{{title}}", (isset($link['title'])) ? $link['title'] : '', $pre_content);
				
				$temp_content = str_replace("{{url}}", (isset($link['url'])) ? $link['url'] : '', $temp_content);
			
				$temp_content = str_replace("{{class}}", (isset($link['attributes']['class'])) ? $link['attributes']['class'] : '', $temp_content);

				$temp_content = str_replace("{{symname}}", (isset($link['symname'])) ? $link['symname'] : '', $temp_content);
			
				$temp_content = str_replace("{{notif_type}}", (isset($link['notification_type'])) ? $link['notification_type'] : '', $temp_content);

				$temp_content = str_replace("{{notif_count}}", (isset($link['count'])) ? $link['count'] : '', $temp_content);				
				
				$content .= $temp_content;
			}


			return $content;

		});

		Theme::render_modifier("login",function($data){
			// dd($data);
			usort($data, function($a ,$b){

				if ($a['priority'] == $b['priority']) {
			        return 0;
			    }
			    return ($a['priority']  < $b['priority'] ) ? -1 : 1;
			});
			//$html = '<a href = "'. $url .'"> <button class="social_gplus"><i class="fa fa-google-plus"></i>Login With Google</button> </a>';

			$pre_content = '<a href ="{{url}}" class="{{class}}"> <i class="{{icon_class}}"></i> <span class="btn-txt"> {{title}}</span>  </a>';

			$content = '';
			$count = 0;
			
			if(UtilityRepository::get_setting('no_social_logins') == '')
				$no_social_logins = 1;
			else
				$no_social_logins = UtilityRepository::get_setting('no_social_logins');

			$dropdown = false;
			
			foreach ($data as $link) {
				$count++;
				

				$temp_content = '';

				$temp_content = str_replace("{{title}}", (isset($link['title'])) ? $link['title'] : '', $pre_content);
				
				$temp_content = str_replace("{{url}}", (isset($link['url'])) ? $link['url'] : '', $temp_content);
			
				$temp_content = str_replace("{{class}}", (isset($link['attributes']['class'])) ? $link['attributes']['class'] : '', $temp_content);

				$temp_content = str_replace("{{icon_class}}", (isset($link['icon_class'])) ? $link['icon_class'] : '', $temp_content);
				
				if($count <= $no_social_logins)
				{
					$content .= '<li>'.$temp_content.'</li>';
					
				}
				else
				{
					if($dropdown == false)
					{
						$content .= '<span class="dropdown social-dropdown-div display_in_block">
	                    <button class="btn btn-primary dropdown-toggle profile_drop_menu" type="button" data-toggle="dropdown"><i class="material-icons social-more-icon">more_horiz</i></button>
	                    <ul class="dropdown-menu social-dropdown-div-ul">';	
	                    $dropdown = true;
					}

					$content .= $temp_content;
				}

			}
			$content .= '</ul></span>';
			$content.= <<<SCRIPT
<script>
	$(document).ready(function(){
		$('.social-dropdown-div').hover(function() {
			$(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(500);
		}, function() {
			  $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(500);
		});
	});
</script>
<style>
	.social-dropdown-div-ul a{
		width: 100%;
    	margin-bottom: 5px;
	}
</style>
SCRIPT;
			return $content;
		});
	}
}
