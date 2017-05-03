<?php
use Illuminate\Support\Facades\Storage;
use App\Components\Api;
use App\Models\Settings;

use App\Models\User;

use App\Components\Presenter;

use App\Components\Plugin;


use App\Repositories\TestUserRepository;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


// admin routes below this 
// admin login and admin middleware
//admin login routes
Route::group(['middleware' => 'afterAdminLogin'], function(){   
	Route::get('admin/login', 'Admin\AuthenticationController@showLogin');
    Route::post('admin/login', 'Admin\AuthenticationController@doLogin');
});

Route::get('admin/test-https', function(){
	return response()->json(["test_https_message" => "HTTPS_TEST_OK"]);
});


Route::group(['middleware' => 'admin'], function(){
    

    Route::get('admin/users/usermanagement', 'Admin\UserManagementController@showUserManagement');
    Route::get('/admin/users/deactivate_usermanagement', 'Admin\UserManagementController@showDeactivatedUserManagement');
	Route::post('/admin/users/usermanagement/credit-users', 'Admin\UserManagementController@creditUsers');
	Route::post('admin/users/usermanagement/activate-superpower-users', 'Admin\UserManagementController@activateUsersSuperpower');
	Route::post('/admin/users/usermanagement/doaction', 'Admin\UserManagementController@doUserManagementAction');


	Route::get('/admin/fb_photos_import/{val}', 'AdminController@fb_photos_import');

    Route::get('admin/users/adminmanagement', 'Admin\AdminManagementController@showAdminManagement');
    Route::post('admin/users/adminmanagement', 'Admin\AdminManagementController@updateAdmin');
    
	Route::get('/admin', 'Admin\DashboardController@showDashboard');
	Route::get('/admin/dashboard', 'Admin\DashboardController@showDashboard');

	Route::post('/admin/users/create', 'AdminController@create');
	Route::get('/admin/users/update/{id}', 'AdminController@update');
	Route::post('/admin/users/updateUser/{id}', 'AdminController@updateUser');	

	Route::get('/admin/socialSettings', 'AdminController@socialSettings');
	Route::post('/admin/socialSettings/facebook', 'AdminController@facebook');
	Route::get('/admin/gatewaySettings', 'AdminController@gatewaySettings');
	Route::post('/admin/gatewaySettings/paypal', 'AdminController@paypal');
	Route::get('/admin/financeSettings', 'Admin\FinanceController@financeSettings');
	
	Route::post('/admin/settings/emailContent', 'AdminController@emailContent');
	Route::get('/admin/settings/socialLoginSettings', 'Admin\SocialLoginsController@show');
	Route::post('/admin/settings/socialLoginSettings', 'Admin\SocialLoginsController@save_priority');
	//end of code


	Route::post('/admin/add_gateway_package', 'PaymentGatewayController@add_gateway_package');
	Route::post('/admin/remove_gateway_package', 'PaymentGatewayController@remove_gateway_package');


    //admin logout
    Route::get('admin/logout', 'Admin\AuthenticationController@doLogout');
    

    Route::post('/admin/usermanagement/users/delete', 'Admin\UserManagementController@deleteUser');
    
});



/* Routes of profile settings for admin panel */
Route::group(['middleware' => 'admin'], function(){

	Route::get('admin/settings/profile', 'Admin\ProfileSettingsController@showSettings');
	Route::post('admin/settings/profile/save/profile-fields-mode', 'Admin\ProfileSettingsController@saveProfileFieldsMode');
	Route::post('admin/settings/profile/save/advance-filter-setting', 'Admin\ProfileSettingsController@saveAdvanceFilterSetting');
	Route::post('admin/settings/profile/save/filter-range', 'Admin\ProfileSettingsController@saveFilterRangeSettings');

});




Route::group(['middleware' => 'admin'], function(){

	Route::get('admin/profilefields', 'Admin\ProfileManageController@show_profilefields');
    Route::post('admin/profilefields/add_section', 'Admin\ProfileManageController@post_add_section');
    Route::post('admin/profilefields/add_fieldstosection', 'Admin\ProfileManageController@post_add_field');
    Route::post('/admin/profilefields/add_fieldoption', 'Admin\ProfileManageController@post_add_field_option');
    Route::post('/admin/profilefields/delete_section', 'Admin\ProfileManageController@post_delete_section');
    Route::post('/admin/profilefields/delete_field', 'Admin\ProfileManageController@post_delete_field');
    Route::post('/admin/profilefields/delete_option', 'Admin\ProfileManageController@post_delete_option');
    Route::post('admin/profilefields/edit_field', 'Admin\ProfileManageController@edit_field');

});




//admin theme management routes
Route::group(['middleware' => 'admin'], function(){

	Route::get('admin/themes', 'Admin\ThemeController@showThemes');
	Route::get('/admin/themes/screenshot/{themename}', 'Admin\ThemeController@renderScreenshot');
	Route::get('admin/theme/activate', 'Admin\ThemeController@activateTheme');
    Route::get('admin/theme/install', 'Admin\ThemeController@installTheme');
    Route::get('/admin/childtheme/deactivate', 'Admin\ThemeController@deactivateChildTheme');
    Route::post('/admin/setCreditsScreenshot', 'Admin\ThemeController@setCreditsScreenshot');
});



//all credit management routes
Route::group(['middleware' => 'admin'], function(){

	Route::get('/admin/creditManage', 'Admin\CreditManageController@creditManage');
	
	Route::post('/admin/creditManage/defaultCredAdd', 'Admin\CreditManageController@defaultCredAdd');
	Route::post('/admin/creditManage/credAddAll', 'Admin\CreditManageController@credAddAll');
	Route::post('/admin/creditManage/spotCred', 'Admin\CreditManageController@spotCred');
	Route::post('/admin/creditManage/riseupCred', 'Admin\CreditManageController@riseupCred');
	
	Route::post('/admin/creditManage/set/currency', 'Admin\CreditManageController@setCurrency');
	
	Route::post('admin/credit_package/add', 'Admin\CreditManageController@addPackage');
	Route::get('admin/credit_package/activate/{id}', 'Admin\CreditManageController@activate');
	Route::get('admin/credit_package/deactivate/{id}', 'Admin\CreditManageController@deactivate');
	
	Route::post('admin/superpower_package/add', 'Admin\CreditManageController@addSuperPowerPackage');
	Route::get('/admin/superpower_package/activate/{id}', 'Admin\CreditManageController@superPowerActivate');
	Route::get('/admin/superpower_package/deactivate/{id}', 'Admin\CreditManageController@superPowerdeactivate');


	Route::post('/admin/credits/double-credits-superpowers', 'Admin\CreditManageController@doubleCreditsSuperpowers');
	Route::post('/admin/credits/spotlight-only-superpowers', 'Admin\CreditManageController@spotlightOnlySuperpowers');
	Route::post('/admin/credits/peoplenearby-only-superpowers', 'Admin\CreditManageController@peoplenearbyOnlySuperpowers');
	Route::post('/admin/credits/credits-module-available', 'Admin\CreditManageController@creditsModuleAvalable');

});


//all interest management routes
Route::group(['middleware' => 'admin'], function(){

	Route::get('/admin/interests', 'Admin\InterestController@interests');
	Route::get('/admin/interests/delete/{id}', 'Admin\InterestController@deleteInterest');
	Route::post('/admin/interests/addInterest', 'Admin\InterestController@addInterest');

});


//abuse reports admin management routes
Route::group(['middleware' => 'admin'], function(){

	Route::get('/admin/misc/userabuse', 'Admin\AbuseManageController@showUserAbuse');
	Route::post('/admin/abusemanagement/userabuse/doaction', 'Admin\AbuseManageController@doUserAbuseAction');

	Route::get('/admin/misc/photoabuse', 'Admin\AbuseManageController@showPhotoAbuse');
	Route::post('/admin/misc/photoabuse', 'Admin\AbuseManageController@photoAbuseAction');

});


//admin general and limit settings routes
Route::group(['middleware' => 'admin'], function(){

	Route::get('/admin/generalSettings', 'Admin\GeneralSettingsController@generalSettings');
	Route::post('admin/generalSettings/enable-disable-debug-mode', 'Admin\GeneralSettingsController@enableDisableDebugMode');
	Route::post('admin/generalSettings/enable-disable-secure-mode', 'Admin\GeneralSettingsController@enableDisableSecureMode');
	Route::post('/admin/generalSettings/upload-photo-setting-save', 'Admin\GeneralSettingsController@uploadPhotoSettingSave');
	Route::post('/admin/generalSettings/title', 'Admin\GeneralSettingsController@setTitle');
	Route::post('/admin/generalSettings/saveMaxFileSize', 'Admin\GeneralSettingsController@save_max_file_size');
	Route::post('/admin/generalSettings/logo', 'Admin\GeneralSettingsController@logo');
	Route::post('/admin/generalSettings/outerlogo', 'Admin\GeneralSettingsController@outerlogo');
	Route::post('/admin/generalSettings/favicon', 'Admin\GeneralSettingsController@favicon');
	
	Route::post('/admin/generalSettings/backgroundimage', 'Admin\GeneralSettingsController@backgroundImage');
	
	Route::post('/admin/generalSettings/setDefaultImage', 'Admin\GeneralSettingsController@setDefaultImage');

	Route::post('/admin/generalSettings/setDefaultMaleImage', 'Admin\GeneralSettingsController@setDefaultMaleImage');
	
	Route::post('/admin/generalSettings/setDefaultFemaleImage', 'Admin\GeneralSettingsController@setDefaultFemaleImage');
	
	Route::post('/admin/generalSettings/visitors', 'Admin\GeneralSettingsController@visitorSettings');
	
	Route::post('/admin/generalSettings/encounter_limit_users', 'Admin\GeneralSettingsController@encounterLimitSettings');
	
	Route::get('/admin/limitsettings', 'Admin\GeneralSettingsController@limitSettigns');

	Route::get('/admin/settings/limitsettings', 'Admin\GeneralSettingsController@showLimitSettings');
	
	Route::post('/admin/generalSettings/chat_limit_users', 'Admin\GeneralSettingsController@chatLimitSettings');

	
	Route::post('/admin/settings/photo/restriction/set', 'Admin\GeneralSettingsController@setPhotoRestrictionSettings');


	Route::post('admin/generalsettings/delete-background-image', 'Admin\GeneralSettingsController@deleteBackgroundImage');
	Route::post('admin/generalsettings/save-prefer-genders', 'Admin\GeneralSettingsController@savePreferGenders');

	Route::get('admin/settings/maxmind', 'Admin\MaxmindSettingsController@showMaxmindSettings');
	Route::post('admin/settings/maxmind', 'Admin\MaxmindSettingsController@saveMaxMindSettings');

});




//email settings admin routes
Route::group(['middleware' => 'admin'], function(){

	Route::get('/admin/settings/email', 'Admin\EmailSettingController@showEmailSettings');
	Route::post('/admin/settings/email/driver/set', 'Admin\EmailSettingController@setMailDriver');

	Route::post('/admin/settings/email/smtp/set', 'Admin\EmailSettingController@addSMTPEmailSettings');
	
	Route::post('/admin/settings/email/mandrill/set', 'Admin\EmailSettingController@addMANDRILLEmailSettings');
	Route::post('admin/settings/email/test', 'Admin\EmailSettingController@testMailSend');

	
});


//admin plugin management routes
Route::group(['middleware' => 'admin'], function(){

	Route::get('admin/plugins', 'Admin\PluginController@showPlugins');
	Route::get('admin/plugin/activate', 'Admin\PluginController@activatePlugin');
	Route::post('admin/plugin/install', 'Admin\PluginController@installPlugin');
	Route::get('admin/plugin/deactivate', 'Admin\PluginController@deactivatePlugin');

});


/* Billing histories : credit histories and superpower histories */
Route::group(['middleware' => 'admin'], function () {	
	Route::get('admin/billing/superpower-histories', 'Admin\BillingController@superpowerHistories');
	Route::get('admin/billing/credit-histories', 'Admin\BillingController@creditHistories');
});



	