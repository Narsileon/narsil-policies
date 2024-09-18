<?php

namespace Narsil\Policies\Traits;

#region USE

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Narsil\Policies\Interfaces\IHasPermissions;
use Narsil\Policies\Interfaces\IHasRoles;
use Narsil\Policies\Models\ModelHasRole;
use Narsil\Policies\Models\Permission;
use Narsil\Policies\Models\Role;

#endregion

trait HasRoles
{
    /**
     * @return void
     */
    final public static function bootHasRoles(): void
    {
        static::deleting(function ($model)
        {
            $model->roles()->detach();
        });
    }

    #region ATTRIBUTES

    /**
     * @return int
     */
    final public function getLevelAttribute(): int
    {
        $this->loadMissing(IHasRoles::RELATIONSHIP_ROLES);

        $level = $this->roles()->max(Role::LEVEL) ?? 0;

        return $level;
    }

    final public function getRoleAttribute(): ?string
    {
        return $this
            ->{IHasRoles::RELATIONSHIP_ROLES}
            ->sortByDesc(Role::LEVEL)
            ->first()?->{Role::SLUG};
    }

    #endregion

    #region RELATIONSHIPS

    /**
     * @return HasOne
     */
    final public function role(): HasOne
    {
        return $this
            ->roles()
            ->one()
            ->ofMany(Role::LEVEL, 'max');
    }

    /**
     * @return MorphToMany
     */
    final public function roles(): MorphToMany
    {
        return $this->morphToMany(
            Role::class,
            ModelHasRole::RELATIONSHIP_MODEL,
            ModelHasRole::TABLE,
            ModelHasRole::MODEL_ID,
            ModelHasRole::ROLE_ID,
        );
    }

    #endregion

    #region PUBLIC METHODS

    /**
     * @param array<int|string>|int|string $roles
     *
     * @return void
     */
    final public function attachRoles(array|int|string $roles): void
    {
        $roleIds = $this->getRoleIds($roles);

        $this->roles()->attach($roleIds);
    }

    /**
     * @param array<int|string>|int|string $roles
     *
     * @return void
     */
    final public function detachRoles(array|int|string $roles): void
    {
        $roleIds = $this->getRoleIds($roles);

        $this->roles()->detach($roleIds);
    }

    /**
     * @return mixed
     */
    final public function getPermissions(): mixed
    {
        $this->loadMissing(IHasRoles::RELATIONSHIP_ROLES, IHasRoles::RELATIONSHIP_ROLES . '.' . IHasPermissions::RELATIONSHIP_PERMISSIONS);

        $permissions = $this->roles->flatMap(function ($role)
        {
            return $role->{IHasPermissions::RELATIONSHIP_PERMISSIONS};
        })->sort()->values();

        return $permissions;
    }

    /**
     * @param array<int|string> $roles
     *
     * @return bool
     */
    final public function hasAnyRole(array $roles): bool
    {
        $this->loadMissing(IHasRoles::RELATIONSHIP_ROLES);

        $hasAnyRole = false;

        foreach ($roles as $role)
        {
            if ($this->hasRole($role))
            {
                $hasAnyRole = true;

                break;
            }
        }

        return $hasAnyRole;
    }

    /**
     * @return bool
     */
    final public function hasPermission(string $permission): bool
    {
        $hasPermission = $this->getPermissions()->contains(Permission::SLUG, $permission);

        return $hasPermission;
    }

    /**
     * @param int|string $role
     *
     * @return bool
     */
    final public function hasRole(int|string $role): bool
    {
        $this->loadMissing(IHasRoles::RELATIONSHIP_ROLES);

        $hasRole = false;

        if (is_int($role))
        {
            $hasRole = $this->{IHasRoles::RELATIONSHIP_ROLES}->contains(Role::ID, $role);
        }
        else if (is_string($role))
        {
            $hasRole = $this->{IHasRoles::RELATIONSHIP_ROLES}->contains(Role::SLUG, $role);
        }

        return $hasRole;
    }

    /**
     * @param array<int|string> $roles
     *
     * @return bool
     */
    final public function hasRoles(array $roles): bool
    {
        $this->loadMissing(IHasRoles::RELATIONSHIP_ROLES);

        $hasRoles = true;

        foreach ($roles as $role)
        {
            if (!$this->hasRole($role))
            {
                $hasRoles = false;

                break;
            }
        }

        return $hasRoles;
    }

    /**
     * @param array<int|string>|int|string $roles
     *
     * @return void
     */
    final public function syncRoles(array|int|string $roles): void
    {
        $roleIds = $this->getRoleIds($roles);

        $this->roles()->sync($roleIds);
    }

    #endregion

    #region PRIVATE METHODS

    /**
     * @param array<int|string>|int|string $roles
     *
     * @return array<int>
     */
    private function getRoleIds(array|int|string $roles): array
    {
        if (!is_array($roles))
        {
            $roles = [$roles];
        }

        $roleIds = [];

        if (count($roles) > 0)
        {
            $roleIds = Role::query()
                ->whereIn(is_int(reset($roles)) ? Role::ID : Role::SLUG, $roles)
                ->get()
                ->pluck(Role::ID)
                ->toArray();
        }

        return $roleIds;
    }

    #endregion
}
