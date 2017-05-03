<?php
use App\Components\PluginAbstract;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Theme;
use Illuminate\Support\Facades\Auth;
use App\Models\Settings;
use App\Repositories\EmailPluginRepository;
use App\Repositories\NotificationsRepository;
use App\Repositories\Admin\UtilityRepository;

class EmailPlugin extends PluginAbstract
{
	public function website()
    {
        return 'datingframework.com';
    }
    public function author()
    {
        return 'DatingFramework';
    }

    public function description()
    {
        return 'This is the Email Plugin.';
    }

    public function version()
    {
        return '1.0.0';
    }

	public function hooks()
	{

        Plugin::add_hook('send_email',function($data) {

            try {

                $user_notification_setting = (new NotificationsRepository)->getNotifSettingsByType($data->user->id, $data->type);

                $email_setting = EmailPluginRepository::getEmailSettings($data->type);
                
                if($user_notification_setting == null || $user_notification_setting->email == 1)
                {
                    $data->subject = $email_setting->subject;

                    $data->user2 = (property_exists($data, 'user2')) ? $data->user2 : $data->user;

                    $this->setLanguage($data->user);


                    if($email_setting->content_type == 1)
                    {
                        $data->body = explode('.', $email_setting->content)[0];
                        
                        Plugin::fire('send_email_template', $data);
                    }
                    else
                    {
                        
                        $extra_symbols = (property_exists($data, 'extra_symbols')) ? $data->extra_symbols : [];
                        
                        $data->body = EmailPluginRepository::emailContentParser($email_setting->content, $data->user, $data->user2, $extra_symbols);
                        
                        Plugin::fire('send_email_raw', $data);
                    }
                }

                Plugin::apply_hooks('after_mail_send', $data->user);

                return ['success' => 'email sent'];

            } catch(\Exception $e) {
                
                return ['error' => $e];
            } 

        });


		Plugin::add_hook('send_email_raw', function ($data) {
                    
            $user    = $data->user;
            $subject = $data->subject;

            Mail::send('emails.default', ['content' => $data->body], function ($message) use ($user, $subject) {    

                $message->to($user->username, $user->name)->subject($subject);
            });
                
                
		});



		Plugin::add_hook('send_email_template', function ($data) {
           
            $user    = $data->user;
            $subject = $data->subject;
            $footer_text = UtilityRepository::get_setting('footer_text');

            Mail::send('emails.'.$data->body, ['user' => $user, 'user2' => $data->user2, 'footer_text' => $footer_text], function ($message) use ($user, $subject) {  
                
                $message->to($user->username, $user->name)->subject($subject);
            });
    			
		});



        Theme::render_modifier ('admin_email_content', function ($datas) {

            $templates = \App\Repositories\Admin\EmailSettingRepository::getEmailTemplates();

            $view_content = "";

            foreach ($datas as $data) {

                $email_setting = \App\Repositories\Admin\EmailSettingRepository::getEmailSetting($data['email_type']);

                $body         = ($email_setting) ? $email_setting->content : ''; 
                $sub          = ($email_setting) ? $email_setting->subject : '';
                $content_type = ($email_setting) ? $email_setting->content_type : '';

                $view_content .= view('admin.mail_content_template', [
                    'email_type'     => $data['email_type'],
                    'content_type'   => $content_type,
                    'mail_id'        => \App\Repositories\Admin\EmailSettingRepository::create_mail_id($data['title']),
                    'heading'        => $data['heading'],
                    'title'          => $data['title'],
                    'mailbodykey'    => $data['mailbodykey'],
                    'mailsubjectkey' => $data['mailsubjectkey'],
                    'body'           => $body,
                    'subject'        => $sub,
                    'templates'      => $templates,
                    ])->render();

            }

            return $view_content;

        });












        Theme::hook ('admin_content_links', function () {

            $url = url('/admin/contents/email');

            $link = '<li><a href="'. $url .'"><i class="fa fa-circle-o"></i>'.trans('admin.menu_content_email') .'</a></li>';

            return $link;

        });

        Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading'        => 'Change Email Address ',
                    'title'          => 'ChangeEmailAddress/Username',
                    'mailbodykey'    => 'changeEmailBody',
                    'mailsubjectkey' => 'changeEmailSubject',
                    'email_type'     => 'change_email',
                ),

            );
        });

        Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading'        => 'Change Password ',
                    'title'          => 'Change Password',
                    'mailbodykey'    => 'changePasswordBody',
                    'mailsubjectkey' => 'changePasswordSubject',
                    'email_type'     => 'change_password',
                ),

            );
        });

		Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading'        => 'Activation Mail Settings',
                    'title'          => 'Account Activation',
                    'mailbodykey'    => 'activateBody',
                    'mailsubjectkey' => 'activateSubject',
                    'email_type'     => 'account_activation',
                ),

            );
        });

        Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading'        => 'Password recover Mail Settings',
                    'title'          => 'Password Recovery',
                    'mailbodykey'    => 'passwordBody',
                    'mailsubjectkey' => 'passwordSubject',
                    'email_type'     => 'password_recover',
                ),

            );
        });

        Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading'        => 'New User Reminder Settings',
                    'title'          => 'New User Reminder',
                    'mailbodykey'    => 'newUserBody',
                    'mailsubjectkey' => 'newUserSubject',
                    'email_type'     => 'new_user_reminder',
                ),

            );
        });

        Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading'        => 'Birthday Settings',
                    'title'          => 'Birthday Wish',
                    'mailbodykey'    => 'birthdayBody',
                    'mailsubjectkey' => 'birthdaySubject',
                    'email_type'     => 'birthday',
                ),

            );
        });

        Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading'        => 'Birthday Reminder Settings',
                    'title'          => 'Birthday Reminder',
                    'mailbodykey'    => 'birthdayReminderBody',
                    'mailsubjectkey' => 'birthdayReminderSubject',
                    'email_type'     => 'birthday_reminder',
                ),

            );
        });

        Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading'        => 'Dating Reminder Settings',
                    'title'          => 'Dating Reminder',
                    'mailbodykey'    => 'datingReminderBody',
                    'mailsubjectkey' => 'datingReminderSubject',
                    'email_type'     => 'dating_reminder',
                ),

            );
        });

        Theme::hook('admin_email_content', function(){
            return array(
                array(
                    'heading'        => 'Unread Message Reminder Settings',
                    'title'          => 'Unread Message Reminder',
                    'mailbodykey'    => 'unreadMessageReminderBody',
                    'mailsubjectkey' => 'unreadMessageReminderSubject',
                    'email_type'     => 'unread_message_reminder',
                ),

            );
        });

	}

    public function settings()
    {
        if(isset($this->settingsModel)) {
            return $this->settingsModel;
        }

        return $this->settingsModel = app('App\Models\Settings');
    }

    public function setLanguage($user) {

        if($user->language != '') {
            App::setLocale($user->language);
        } else {
             App::setLocale($this->settings()->get('default_language'));
        }
    }

	public function routes()
	{
        Route::post('/send_email/', 'App\Http\Controllers\EmailPluginController@send_mail');
		Route::post('/plugin/upload/email/image', 'App\Http\Controllers\EmailPluginController@uploadEmailImage');

        Route::get('/admin/contents/email', 'App\Http\Controllers\EmailPluginController@showEmailContents');
        Route::post('/admin/settings/email/save', 'App\Http\Controllers\EmailPluginController@saveEmailSettings');
        Route::post('/admin/email/footer/save', 'App\Http\Controllers\EmailPluginController@saveFooterValue');

	}


	public function autoload()
	{
		return array(
            Plugin::path('EmailPlugin/controllers'),
            Plugin::path('EmailPlugin/models'),
			Plugin::path('EmailPlugin/repositories'),
		);
	}
}

