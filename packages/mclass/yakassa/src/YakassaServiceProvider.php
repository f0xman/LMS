<?php

namespace Mclass\Yakassa;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class YakassaServiceProvider extends ServiceProvider
{
    /** @var Application */
    protected $app;

    protected $defer = true;

    public function register(): void
    {
        $this->app->bind('mclass.yakassa', function ($app) {
            $config = $app['config']['services.yakassa'];

            $yaKassa = new Yakassa(
                $config['shop_id'], $config['shop_password']
            );

            return $yaKassa;
        });
        $this->app->alias('mclass.yakassa', Yakassa::class);
    }

    public function provides(): array
    {
        return [
            'mclass.yakassa', Yakassa::class
        ];
    }
}
