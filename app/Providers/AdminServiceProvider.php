<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
	   

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
	   $this->registerSingletonClasses();
    }


    protected function registerSingletonClasses() {

        /* PhotoAbuseReport $photoAbuseReport, UserAbuseReport $usreAbuseReport */
        $this->app->singleton('AbuseManageRepository', function ($app) {
         
            return new App\Repositories\Admin\AbuseManageRepository(
                $this->app->make("App\Models\PhotoAbuseReport"), 
                $this->app->make("App\Models\UserAbuseReport")
            );

        });

        /* Admin $admin */
        $this->app->singleton('AdminManagementRepository', function ($app) {
         
            return new App\Repositories\Admin\AdminManagementRepository($this->app->make("App\Models\Admin"));
            
        });

        /* Admin $admin */
        $this->app->singleton('AuthenticationRepository', function ($app) {
         
            return new App\Repositories\Admin\AuthenticationRepository($this->app->make("App\Models\Admin"));
            
        });

        /* SuperpowerHistory $superpowerHistory, CreditHistory $creditHistory, Transaction $transaction */
        $this->app->singleton('FinanceRepository', function ($app) {
         
            return new App\Repositories\Admin\FinanceRepository(
                $this->app->make("App\Models\SuperpowerHistory"),
                $this->app->make("App\Models\CreditHistory"),
                $this->app->make("App\Models\Transaction")
            );
            
        });


        /* Package $creditPackage, Credit $credit, CreditHistory $creditHistory, SuperPowerPackages $superpowerPackage */
        $this->app->singleton('CreditManageRepository', function ($app) {
         
            return new App\Repositories\Admin\CreditManageRepository(
                $this->app->make("App\Models\Package"),
                $this->app->make("App\Models\Credit"),
                $this->app->make("App\Models\CreditHistory"),
                $this->app->make("App\Models\SuperPowerPackages")
            );
            
        });

        /* User $user */
        $this->app->singleton('DashboardRepository', function ($app) {
         
            return new App\Repositories\Admin\DashboardRepository(
                $this->app->make("App\Models\User")
            );
            
        });

        /* Interests $interests, UserInterests $userInterests */
        $this->app->singleton('InterestManageRepository', function ($app) {
         
            return new App\Repositories\Admin\InterestManageRepository(
                $this->app->make("App\Models\Interests"),
                $this->app->make("App\Models\UserInterests")
            );
            
        });

        /* Plugins $plugins */
        $this->app->singleton('PluginRepository', function ($app) {
         
            return new App\Repositories\Admin\PluginRepository(
                $this->app->make("App\Models\Plugins")
            );
            
        });

        /* Fields $fields, FieldSections $fieldSections, FieldOptions $fieldOptions, UserFields $userFields */
        $this->app->singleton('ProfileManageRepository', function ($app) {
         
            return new App\Repositories\Admin\ProfileManageRepository(
                $this->app->make("App\Models\Fields"),
                $this->app->make("App\Models\FieldSections"),
                $this->app->make("App\Models\FieldOptions"),
                $this->app->make("App\Models\UserFields")
            );
            
        });

        /* SocialLogins $socialLogins */
        $this->app->singleton('SocialLoginsRepository', function ($app) {
         
            return new App\Repositories\Admin\SocialLoginsRepository(
                $this->app->make("App\Models\SocialLogins")
            );
            
        });

        /* Themes $themes */
        $this->app->singleton('ThemeManageRepository', function ($app) {
         
            return new App\Repositories\Admin\ThemeManageRepository(
                $this->app->make("App\Models\Themes")
            );
            
        });

        
        $this->app->singleton('UserManagementRepository', function ($app) {
         
            return new App\Repositories\Admin\UserManagementRepository(
                $this->app->make("App\Models\User"),
                $this->app->make("App\Models\BlockUsers"),
                $this->app->make("App\Models\CreditHistory"),
                $this->app->make("App\Models\Credit"),
                $this->app->make("App\Models\Encounter"),
                $this->app->make("App\Models\Match"),
                $this->app->make("App\Models\Photo"),
                $this->app->make("App\Models\SuperpowerHistory"),
                $this->app->make("App\Models\UserSuperPowers"),
                $this->app->make("App\Models\UserAbuseReport"),
                $this->app->make("App\Models\UserSettings"),
                $this->app->make("App\Models\UserSocialLogin"),
                $this->app->make("App\Models\UserFields"),
                $this->app->make("App\Models\PhotoAbuseReport"),
                $this->app->make("App\Models\Profile"),
                $this->app->make("App\Models\Visitor"),
                $this->app->make("App\Models\RiseUp"),
                $this->app->make("App\Models\Spotlight"),
                $this->app->make("App\Models\Notifications"),
                $this->app->make("App\Models\NotificationSettings")
            );
            
        });

    }

}
