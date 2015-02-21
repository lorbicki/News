<?php
namespace TypiCMS\Modules\News\Providers;

use Config;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Lang;
use TypiCMS\Modules\News\Models\News;
use TypiCMS\Modules\News\Models\NewsTranslation;
use TypiCMS\Modules\News\Repositories\CacheDecorator;
use TypiCMS\Modules\News\Repositories\EloquentNews;
use TypiCMS\Observers\FileObserver;
use TypiCMS\Observers\SlugObserver;
use TypiCMS\Services\Cache\LaravelCache;
use View;

class ModuleProvider extends ServiceProvider
{

    public function boot()
    {

        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php', 'typicms.news'
        );

        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'news');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'news');

        $this->publishes([
            __DIR__ . '/../views' => base_path('resources/views/vendor/news'),
        ], 'views');
        $this->publishes([
            __DIR__ . '/../database' => base_path('database'),
        ], 'migrations');

        AliasLoader::getInstance()->alias(
            'News',
            'TypiCMS\Modules\News\Facades\Facade'
        );

        // Observers
        NewsTranslation::observe(new SlugObserver);
        News::observe(new FileObserver);
    }

    public function register()
    {

        $app = $this->app;

        /**
         * Register route service provider
         */
        $app->register('TypiCMS\Modules\News\Providers\RouteServiceProvider');

        /**
         * Sidebar view composer
         */
        $app->view->composer('core::admin._sidebar', 'TypiCMS\Modules\News\Composers\SideBarViewComposer');

        $app->bind('TypiCMS\Modules\News\Repositories\NewsInterface', function (Application $app) {
            $repository = new EloquentNews(new News);
            if (! Config::get('app.cache')) {
                return $repository;
            }
            $laravelCache = new LaravelCache($app['cache'], ['news', 'galleries'], 10);

            return new CacheDecorator($repository, $laravelCache);
        });

    }
}
