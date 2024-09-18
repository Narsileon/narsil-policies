<?php

namespace Narsil\Policies;

#region USE

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Narsil\Policies\Commands\SyncPermissionsCommand;
use Narsil\Policies\Models\Permission;
use Narsil\Policies\Models\Role;
use Narsil\Policies\Policies\PermissionPolicy;
use Narsil\Policies\Policies\RolePolicy;

#endregion

/**
 * @version 1.0.0
 *
 * @author Jonathan Rigaux
 */
final class NarsilPoliciesServiceProvider extends ServiceProvider
{
    #region PUBLIC METHODS

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->bootCommands();
        $this->bootMigrations();
        $this->bootPolicies();
        $this->bootPublishes();
        $this->bootTranslations();

        Gate::before([$this, 'before']);
    }

    /**
     * @param $user
     * @param string $ability
     *
     * @return bool
     */
    public function before($user, string $ability): bool
    {
        if ($user->hasRole('super-admin'))
        {
            return true;
        }
        else
        {
            return $user->hasPermission($ability);
        }
    }

    #endregion

    #region PRIVATE METHODS

    /**
     * @return void
     */
    private function bootCommands(): void
    {
        $this->commands([
            SyncPermissionsCommand::class,
        ]);
    }

    /**
     * @return void
     */
    private function bootMigrations(): void
    {
        $this->loadMigrationsFrom([
            __DIR__ . '/../database/migrations',
        ]);
    }

    /**
     * @return void
     */
    private function bootPolicies(): void
    {
        Gate::policy(Permission::class, PermissionPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
    }

    /**
     * @return void
     */
    private function bootPublishes(): void
    {
        $this->publishes([
            __DIR__ . '/Config' => config_path(),
        ], 'config');
    }

    /**
     * @return void
     */
    private function bootTranslations(): void
    {
        $this->loadJsonTranslationsFrom(__DIR__ . '/../lang', 'policies');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'policies');
    }

    #endregion
}
