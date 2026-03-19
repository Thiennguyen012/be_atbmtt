<?php

namespace App\Providers;

use App\Repositories\User\UserInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\Order\OrderInterface;
use App\Repositories\Order\OrderRepository;
use App\Repositories\CustomerOrder\CustomerOrderInterface;
use App\Repositories\CustomerOrder\CustomerOrderRepository;
use App\Services\Order\OrderService;
use App\Services\CustomerOrderService\CustomerOrderService;
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

        // Service bindings
        $this->app->bind(CustomerOrderService::class, function ($app) {
            return new CustomerOrderService($app->make(CustomerOrderInterface::class));
        });

        $this->app->bind(OrderService::class, function ($app) {
            return new OrderService(
                $app->make(OrderInterface::class),
                $app->make(CustomerOrderService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
