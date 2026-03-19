<?php

namespace App\Providers;

use App\Repositories\User\UserInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\Order\OrderInterface;
use App\Repositories\Order\OrderRepository;
use App\Repositories\CustomerOrder\CustomerOrderInterface;
use App\Repositories\CustomerOrder\CustomerOrderRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(OrderInterface::class, OrderRepository::class);
        $this->app->bind(CustomerOrderInterface::class, CustomerOrderRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
