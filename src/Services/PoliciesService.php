<?php

namespace Narsil\Policies\Services;

#region USE

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

#endregion

/**
 * @version 1.0.0
 *
 * @author Jonathan Rigaux
 */
final class PoliciesService
{
    #region PUBLIC METHODS

    /**
     * @param string $modelClass
     *
     * @return array
     */
    public static function getAllPermissions(string $modelClass): array
    {
        $policy = Gate::getPolicyFor($modelClass);

        if (!$policy)
        {
            return [];
        }

        $create = $policy->create(Auth::user(), $modelClass);
        $delete = $policy->delete(Auth::user(), $modelClass);
        $show = $policy->view(Auth::user(), $modelClass);
        $update = $policy->update(Auth::user(), $modelClass);
        $view = $policy->view(Auth::user(), $modelClass);

        return compact(
            'create',
            'delete',
            'show',
            'update',
            'view',
        );
    }

    /**
     * @param string $modelClass
     * @param string $ability
     *
     * @return string|null
     */
    public static function getPermissionName(string $modelClass, string $ability): string|null
    {
        $permissionName = null;

        try
        {
            $modelInstance = new $modelClass();

            $table = $modelInstance->getTable();

            $permissionName = $table . '_' . $ability;
        }
        catch (Exception $exception)
        {
        }

        return $permissionName;
    }

    #endregion
}
