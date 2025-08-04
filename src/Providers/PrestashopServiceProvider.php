<?php

namespace Webkul\Prestashop\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webkul\Prestashop\Console\Commands\PrestashopInstaller;
use Webkul\Prestashop\Console\Commands\PrestashopMappingProduct;
use Webkul\Theme\ViewRenderEventManager;

class PrestashopServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        Route::middleware('web')->group(__DIR__.'/../Routes/prestashop-routes.php');

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migration');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'prestashop');
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'prestashop');

        $this->app->register(ModuleServiceProvider::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                PrestashopInstaller::class,
                PrestashopMappingProduct::class,
            ]);
        }

        Event::listen('unopim.admin.layout.head', static function (ViewRenderEventManager $viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('prestashop::style');
        });

        $this->publishes([
            __DIR__.'/../../publishable' => public_path('themes'),
        ], 'prestashop-config');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/menu.php',
            'menu.admin'
        );
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/acl.php', 'acl'
        );
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/exporters.php', 'exporters'
        );
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/importers.php', 'importers'
        );
        $this->mergeConfigFrom(
            __DIR__.'/../Config/unopim-vite.php', 'unopim-vite.viters'
        );
    }
}
