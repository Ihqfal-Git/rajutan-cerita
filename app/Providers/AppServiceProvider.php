<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::forceScheme('https');
        if (env('DATABASE_URL')) {
        $url = parse_url(env('DATABASE_URL'));
        config([
            'database.default'                         => 'pgsql',
            'database.connections.pgsql.host'          => $url['host'],
            'database.connections.pgsql.port'          => $url['port'] ?? 5432,
            'database.connections.pgsql.database'      => ltrim($url['path'], '/'),
            'database.connections.pgsql.username'      => $url['user'],
            'database.connections.pgsql.password'      => $url['pass'],
            'database.connections.pgsql.sslmode'       => 'require',
        ]);
        }
    }
}
