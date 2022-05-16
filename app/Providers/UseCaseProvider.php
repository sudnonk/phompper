<?php

namespace App\Providers;

use App\UseCase\PositionUseCase;
use Illuminate\Support\ServiceProvider;

class UseCaseProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot():void
    {
        $this->app->bind(PositionUseCase::class);
    }
}
