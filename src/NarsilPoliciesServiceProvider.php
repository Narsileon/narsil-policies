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
        Gate::before([$this, 'before']);
        Gate::guessPolicyNamesUsing([$this, 'guessPolicyNames']);
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

    /**
     * @param string $modelClass
     *
     * @return string
     */
    public function guessPolicyNames(string $modelClass): string
    {
        $classBaseName = class_basename($modelClass);

        $policy = "Narsil\\Framework\\Policies\\{$classBaseName}Policy";

        if (!class_exists($policy))
        {
            $policy = "App\\Policies\\{$classBaseName}Policy";
        }

        return $policy;
    }

    #endregion
}
