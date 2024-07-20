<?php

namespace Narsil\Policies;

#region USE

use Illuminate\Auth\Access\HandlesAuthorization;
use Narsil\Policies\Services\PoliciesService;

#endregion

/**
 * @version 1.0.0
 *
 * @author Jonathan Rigaux
 */
abstract class AbstractPolicy
{
    use HandlesAuthorization;

    #region CONSTRUCTOR

    /**
     * @param string $modelClass
     * @param bool $canCreate
     * @param bool $canDelete
     * @param bool $canUpdate
     * @param bool $canView
     *
     * @return void
     */
    public function __construct(
        string $modelClass,
        bool $canView = true,
        bool $canCreate = true,
        bool $canUpdate = true,
        bool $canDelete = true,
    )
    {
        $this->modelClass = $modelClass;
        $this->canView = $canView;
        $this->canCreate = $canCreate;
        $this->canUpdate = $canUpdate;
        $this->canDelete = $canDelete;
    }

    #endregion

    #region PROPERTIES

    /**
     * @var string
     */
    protected readonly string $modelClass;

    /**
     * @var bool
     */
    public readonly bool $canCreate;
    /**
     * @var bool
     */
    public readonly bool $canDelete;
    /**
     * @var bool
     */
    public readonly bool $canUpdate;
    /**
     * @var bool
     */
    public readonly bool $canView;

    #endregion

    #region PUBLIC METHODS

    /**
     * @param $user
     *
     * @return bool
     */
    final public function view($user): bool
    {
        if (!$this->canView)
        {
            return false;
        }

        $permission = PoliciesService::getPermissionName($this->modelClass, 'view');

        return $user?->can($permission);
    }

    /**
     * @param $user
     *
     * @return bool
     */
    final public function create($user): bool
    {
        if (!$this->canCreate)
        {
            return false;
        }

        $permission = PoliciesService::getPermissionName($this->modelClass, 'create');

        return $user?->can($permission);
    }

    /**
     * @param $user
     *
     * @return bool
     */
    final public function update($user): bool
    {
        if (!$this->canUpdate)
        {
            return false;
        }

        $permission = PoliciesService::getPermissionName($this->modelClass, 'update');

        return $user?->can($permission);
    }

    /**
     * @param $user
     *
     * @return bool
     */
    final public function delete($user): bool
    {
        if (!$this->canDelete)
        {
            return false;
        }

        $permission = PoliciesService::getPermissionName($this->modelClass, 'delete');

        return $user?->can($permission);
    }

    #endregion
}
