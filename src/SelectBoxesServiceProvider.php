<?php

namespace Webdevjohn\SelectBoxes;

use Illuminate\Support\ServiceProvider;
use Webdevjohn\SelectBoxes\Commands\MakeGroup;

class SelectBoxesServiceProvider extends ServiceProvider
{
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
        $this->commands(MakeGroup::class);
    }
}
