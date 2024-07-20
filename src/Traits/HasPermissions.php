<?php

namespace Narsil\Policies\Traits;

#region USE

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Narsil\Policies\Interfaces\IHasPermissions;
use Narsil\Policies\Models\ModelHasPermission;
use Narsil\Policies\Models\Permission;

#endregion

trait HasPermissions
{
    /**
     * @return void
     */
    public static function bootHasPermissions(): void
    {
        static::deleting(function ($model)
        {
            $model->roles()->detach();
        });
    }

    #region RELATIONSHIPS

    /**
     * @return MorphToMany
     */
    final public function permissions(): MorphToMany
    {
        return $this->morphToMany(
            Permission::class,
            ModelHasPermission::RELATIONSHIP_MODEL,
            ModelHasPermission::TABLE,
            ModelHasPermission::MODEL_ID,
            ModelHasPermission::PERMISSION_ID,
        );
    }

    #endregion

    #region PUBLIC METHODS

    /**
     * @param array<int|string>|int|string $permissions
     *
     * @return void
     */
    final public function attachPermissions(array|int|string $permissions): void
    {
        $permissionIds = $this->getPermissionIds($permissions);

        $this->permissions()->attach($permissionIds);
    }

    /**
     * @param array<int|string>|int|string $roles
     *
     * @return void
     */
    final public function detachPermissions(array|int|string $permissions): void
    {
        $permissionIds = $this->getPermissionIds($permissions);

        $this->permissions()->detach($permissionIds);
    }

    /**
     * @param array<int|string> $roles
     *
     * @return bool
     */
    final public function hasAnyPermission(array $permissions): bool
    {
        $this->loadMissing(IHasPermissions::RELATIONSHIP_PERMISSIONS);

        $hasAnyPermission = false;

        foreach ($permissions as $permission)
        {
            if ($this->hasPermission($permission))
            {
                $hasAnyPermission = true;

                break;
            }
        }

        return $hasAnyPermission;
    }

    /**
     * @param int|string $role
     *
     * @return bool
     */
    final public function hasPermission(int|string $permission): bool
    {
        $this->loadMissing(IHasPermissions::RELATIONSHIP_PERMISSIONS);

        $hasPermission = false;

        if (is_int($permission))
        {
            $hasPermission = $this->{IHasPermissions::RELATIONSHIP_PERMISSIONS}->contains(Permission::ID, $permission);
        }
        else if (is_string($permission))
        {
            $hasPermission = $this->{IHasPermissions::RELATIONSHIP_PERMISSIONS}->contains(Permission::NAME, $permission);
        }

        return $hasPermission;
    }

    /**
     * @param array<int|string> $permissions
     *
     * @return bool
     */
    final public function hasPermissions(array $permissions): bool
    {
        $this->loadMissing(IHasPermissions::RELATIONSHIP_PERMISSIONS);

        $hasPermissions = true;

        foreach ($permissions as $permission)
        {
            if (!$this->hasRole($permission))
            {
                $hasPermissions = false;

                break;
            }
        }

        return $hasPermissions;
    }

    /**
     * @param array<int|string>|int|string $permissions
     *
     * @return void
     */
    final public function syncPermissions(array|int|string $permissions): void
    {
        $permissionIds = $this->getPermissionIds($permissions);

        $this->permissions()->sync($permissionIds);
    }

    #endregion

    #region PRIVATE METHODS

    /**
     * @param array<int|string>|int|string $roles
     *
     * @return array<int>
     */
    private function getPermissionIds(array|int|string $permissions): array
    {
        if (!is_array($permissions))
        {
            $permissions = [$permissions];
        }

        $permissionIds = [];

        if (count($permissions) > 0)
        {
            $permissionIds = Permission::query()
                ->whereIn(is_int(reset($permissions)) ? Permission::ID : Permission::NAME, $permissions)
                ->get()
                ->pluck(Permission::ID)
                ->toArray();
        }

        return $permissionIds;
    }

    #endregion
}
