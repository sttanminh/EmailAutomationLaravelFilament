<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Listeners\JobFailureListener;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') !== 'local') { 
            URL::forceScheme('https'); 
        }
        // Event::listen(JobExceptionOccurred::class, JobExceptionListener::class);
        
        // =Event::listen( JobFailureListener::class);
        // Event::listen( JobFailureListener::class);
    }



}
