<?php

namespace LuizFabianoNogueira\AclPermissions;

use Illuminate\Support\ServiceProvider;

class AclPermissionsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Routes/acl-permissions.php');
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');

        $this->publishesMigrations([
            __DIR__ . '/Migrations' => database_path('migrations'),
        ], 'acl-permissions-migrations');

        $this->publishes([
            __DIR__ . '/Config/acl-permissions.php' => config_path('acl-permissions.php'),
        ], 'acl-permissions-config');
    }
}
