<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\Authentication\LoginRepositoryInterface;
use App\Repository\Authentication\LoginRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LoginRepositoryInterface::class, LoginRepository::class);
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
