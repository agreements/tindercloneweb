<?php

use App\Components\PluginInstall;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Components\Plugin;
use App\Models\EmailSettings;


class EmailPluginInstall extends PluginInstall
{
	public function install()
	{
		$this->createEmailSettingsTable();

		/* default email settings save */
		require_once Plugin::path("EmailPlugin/models/EmailSettings.php");

		//forgot password
		$forgot_pasword = new EmailSettings;
		$forgot_pasword->subject = "Password Recovery";
		$forgot_pasword->content = "forgotPassword.blade.php";
		$forgot_pasword->content_type = 1;
		$forgot_pasword->email_type = "password_recover";
		$forgot_pasword->save();


		$account_activation = new EmailSettings;
		$account_activation->subject = "Account Activation Mail";
		$account_activation->content = "activateAccount.blade.php";
		$account_activation->content_type = 1;
		$account_activation->email_type = "account_activation";
		$account_activation->save();



		$new_user_reminder = new EmailSettings;
		$new_user_reminder->subject = "New User Reminder Mail";
		$new_user_reminder->content = "NewUserReminder.blade.php";
		$new_user_reminder->content_type = 1;
		$new_user_reminder->email_type = "new_user_reminder";
		$new_user_reminder->save();


		$birthday = new EmailSettings;
		$birthday->subject = "Birthday";
		$birthday->content = "Birthday.blade.php";
		$birthday->content_type = 1;
		$birthday->email_type = "birthday";
		$birthday->save();


		$birthday_reminder = new EmailSettings;
		$birthday_reminder->subject = "Birthday Reminder";
		$birthday_reminder->content = "BirthdayReminder.blade.php";
		$birthday_reminder->content_type = 1;
		$birthday_reminder->email_type = "birthday_reminder";
		$birthday_reminder->save();


		$dating_reminder = new EmailSettings;
		$dating_reminder->subject = "Dating Reminder";
		$dating_reminder->content = "DatingReminder.blade.php";
		$dating_reminder->content_type = 1;
		$dating_reminder->email_type = "dating_reminder";
		$dating_reminder->save();

		$unread_message_reminder = new EmailSettings;
		$unread_message_reminder->subject = "Unread Message Reminder";
		$unread_message_reminder->content = "UnreadMessageReminder.blade.php";
		$unread_message_reminder->content_type = 1;
		$unread_message_reminder->email_type = "unread_message_reminder";
		$unread_message_reminder->save();


		$liked = new EmailSettings;
		$liked->subject = "Liked Mail";
		$liked->content = "Liked.blade.php";
		$liked->content_type = 1;
		$liked->email_type = "liked";
		$liked->save();


		$match = new EmailSettings;
		$match->subject = "Match Mail";
		$match->content = "Match.blade.php";
		$match->content_type = 1;
		$match->email_type = "match";
		$match->save();


		$visitor = new EmailSettings;
		$visitor->subject = "Visitor Mail";
		$visitor->content = "Visitor.blade.php";
		$visitor->content_type = 1;
		$visitor->email_type = "visitor";
		$visitor->save();

		$change_email = new EmailSettings;
		$change_email->subject = "Email Changed Mail";
		$change_email->content = "activateAccount.blade.php";
		$change_email->content_type = 1;
		$change_email->email_type = "change_email";
		$change_email->save();


		$change_password = new EmailSettings;
		$change_password->subject = "Password Changed Mail";
		$change_password->content = "<p>Your Password has been changed.</p>";
		$change_password->content_type = 0;
		$change_password->email_type = "change_password";
		$change_password->save();




	}

	public function uninstall()
	{
		
	}

	public function createEmailSettingsTable()
	{
		Schema::dropIfExists('email_settings');

  		Schema::create('email_settings', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->longText('subject');
	            $table->longText('content');
	            $table->smallInteger('content_type');
	            $table->string('email_type', 500);
	            $table->timestamps();
	            $table->softDeletes();
        });
	}

}