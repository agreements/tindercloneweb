<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PluginServiceProvider extends ServiceProvider
{
    
    public function boot()
    {
    
        view()->composer('*', 'GlobalComposer');
    }

   
    public function register()
    {
        
    }
}
