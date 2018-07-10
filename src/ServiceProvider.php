<?php

namespace ZanySoft\Widgets;

use ZanySoft\Widgets\Console\WidgetMakeCommand;
use ZanySoft\Widgets\Factories\AsyncWidgetFactory;
use ZanySoft\Widgets\Factories\WidgetFactory;
use ZanySoft\Widgets\Misc\LaravelApplicationWrapper;
use Illuminate\Support\Facades\Blade;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/config.php', 'laravel-widgets'
        );

        $this->app->bind('zanysoft.widget', function () {
            return new WidgetFactory(new LaravelApplicationWrapper());
        });

        $this->app->bind('zanysoft.async-widget', function () {
            return new AsyncWidgetFactory(new LaravelApplicationWrapper());
        });

        $this->app->singleton('zanysoft.widget-group-collection', function () {
            return new WidgetGroupCollection(new LaravelApplicationWrapper());
        });

        $this->app->singleton('command.widget.make', function ($app) {
            return new WidgetMakeCommand($app['files']);
        });

        $this->commands('command.widget.make');

        $this->app->alias('zanysoft.widget', 'ZanySoft\Widgets\Factories\WidgetFactory');
        $this->app->alias('zanysoft.async-widget', 'ZanySoft\Widgets\Factories\AsyncWidgetFactory');
        $this->app->alias('zanysoft.widget-group-collection', 'ZanySoft\Widgets\WidgetGroupCollection');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('laravel-widgets.php'),
        ]);

        $routeConfig = [
            'namespace'  => 'ZanySoft\Widgets\Controllers',
            'prefix'     => 'zanysoft',
            'middleware' => $this->app['config']->get('laravel-widgets.route_middleware', []),
        ];

        if (!$this->app->routesAreCached()) {
            $this->app['router']->group($routeConfig, function ($router) {
                $router->get('load-widget', 'WidgetController@showWidget');
            });
        }

        Blade::directive('widget', function ($expression) {
            return "<?php echo app('zanysoft.widget')->run($expression); ?>";
        });

        Blade::directive('asyncWidget', function ($expression) {
            return "<?php echo app('zanysoft.async-widget')->run($expression); ?>";
        });

        Blade::directive('widgetGroup', function ($expression) {
            return "<?php echo app('zanysoft.widget-group-collection')->group($expression)->display(); ?>";
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['zanysoft.widget', 'zanysoft.async-widget'];
    }
}
