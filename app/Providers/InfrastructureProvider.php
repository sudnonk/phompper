<?php

namespace App\Providers;

use App\Infrastructure\Database\ImageRepository;
use App\Infrastructure\Database\ImageRepositoryInterface;
use App\Infrastructure\Database\PositionRepository;
use App\Infrastructure\Database\PositionRepositoryInterface;
use App\Infrastructure\Storage\GoogleCloudStorage;
use App\Infrastructure\Storage\ImageStorageInterface;
use Illuminate\Support\ServiceProvider;

class InfrastructureProvider extends ServiceProvider
{
    public array $singletons = [
        ImageStorageInterface::class => GoogleCloudStorage::class,
        PositionRepositoryInterface::class => PositionRepository::class,
        ImageRepositoryInterface::class => ImageRepository::class
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
