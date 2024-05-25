<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		if($this->app->environment('production')) {
		\URL::forceScheme('https');
		}

		Schema::DefaultStringLength(99);
		
		  view()->composer('*', function($view){
            $view_name = str_replace('.', '-', $view->getName());
            view()->share('view_name', $view_name);
        });
		
        Validator::extend(
            'recaptcha',
            'App\\Validators\\ReCaptcha@validate'
        );
    
    }
}
