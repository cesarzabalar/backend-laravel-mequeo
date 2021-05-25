<?php

namespace App\Providers;

use App\Repositories\Interfaces\MainCashRegisterRepositoryInterface;
use App\Repositories\Interfaces\MainPaymentRepositoryInterface;
use App\Repositories\Interfaces\CashRegisterRepositoryInterface;
use App\Repositories\Interfaces\LogRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\MainCashRegisterRepository;
use App\Repositories\MainPaymentRepository;
use App\Repositories\CashRegisterRepository;
use App\Repositories\LogRepository;
use App\Repositories\PaymentRepository;

use App\Services\Interfaces\CashRegisterServiceInterface;
use App\Services\Interfaces\LogServiceInterface;
use App\Services\Interfaces\PaymentServiceInterface;
use App\Services\Interfaces\HandlerResponseServiceInterface;
use App\Services\CashRegisterService;
use App\Services\LogService;
use App\Services\PaymentService;
use App\Services\HandlerResponseService;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Repositories
        $this->app->bind(MainCashRegisterRepositoryInterface::class,MainCashRegisterRepository::class);
        $this->app->bind(MainPaymentRepositoryInterface::class,MainPaymentRepository::class);
        $this->app->bind(CashRegisterRepositoryInterface::class,CashRegisterRepository::class);
        $this->app->bind(LogRepositoryInterface::class,LogRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class,PaymentRepository::class);

        // Services
        $this->app->bind(CashRegisterServiceInterface::class,CashRegisterService::class);
        $this->app->bind(PaymentServiceInterface::class,PaymentService::class);
        $this->app->bind(LogServiceInterface::class,LogService::class);
        $this->app->bind(HandlerResponseServiceInterface::class,HandlerResponseService::class);
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
