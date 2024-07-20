<?php

namespace Narsil\Policies\Observers;

#region USE

use Narsil\Policies\Models\Role;

#endregion

/**
 * @version 1.0.0
 *
 * @author Jonathan Rigaux
 */
class RoleObserver
{
    #region PUBLIC METHODS

    /**
     * @param Role $role
     *
     * @return void
     */
    public function saved(Role $role): void
    {
        $this->syncPermissions($role);
    }

    #endregion

    #region PROTECTED METHODS

    /**
     * @param Role $role
     *
     * @return void
     */
    protected function syncPermissions(Role $role): void
    {
        if ($permissions = request()->get(Role::RELATIONSHIP_PERMISSIONS, null))
        {
            $role->syncPermissions($permissions);
        }
    }

    #endregion
}
