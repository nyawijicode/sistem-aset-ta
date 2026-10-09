<?php

namespace App\Providers;

use App\Models\AssetIn;
use App\Models\AssetOut;
use App\Observers\AssetInObserver;
use App\Observers\AssetOutObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        AssetIn::observe(AssetInObserver::class);
        AssetOut::observe(AssetOutObserver::class);
    }
}
