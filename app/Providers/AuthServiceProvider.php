<?php

namespace App\Providers;

use App\Models\Device;
use App\Models\Home;
use App\Policies\DevicePolicy;
use App\Policies\HomePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Home::class => HomePolicy::class,
        Device::class => DevicePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
