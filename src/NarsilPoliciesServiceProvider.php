<?php

namespace Narsil\Policies;

#region USE

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        $this->bootMigrations();

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
    private function bootMigrations(): void
    {
        $this->loadMigrationsFrom([
            __DIR__ . '/../database/migrations',
        ]);
    }

    #endregion
}
