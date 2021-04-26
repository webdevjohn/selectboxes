<?php

namespace Webdevjohn\SelectBoxes;

use Illuminate\Support\ServiceProvider;
use Webdevjohn\SelectBoxes\Commands\MakePage;

class SelectBoxesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/selectboxes.php' => config_path('selectboxes.php'),
        ]);
    }

        
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(MakePage::class);
    }
}
