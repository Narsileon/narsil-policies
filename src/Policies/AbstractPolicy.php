<?php

namespace Narsil\Policies\Policies;

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

    #region CONSTANTS

    /**
     * @var string
     */
    final public const CREATE = 'create';
    /**
     * @var string
     */
    final public const DELETE = 'delete';
    /**
     * @var string
     */
    final public const UPDATE = 'update';
    /**
     * @var string
     */
    final public const VIEW = 'view';

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

    #region AUTHORIZATIONS

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

        $permission = PoliciesService::getPermissionName($this->modelClass, self::VIEW);

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

        $permission = PoliciesService::getPermissionName($this->modelClass, self::CREATE);

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

        $permission = PoliciesService::getPermissionName($this->modelClass, self::UPDATE);

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

        $permission = PoliciesService::getPermissionName($this->modelClass, self::DELETE);

        return $user?->can($permission);
    }

    #endregion

    #region PUBLIC METHODS

    /**
     * @return array
     */
    final public function getAbilities(): array
    {
        return [
            self::CREATE => $this->canCreate,
            self::DELETE => $this->canDelete,
            self::UPDATE => $this->canUpdate,
            self::VIEW => $this->canView,
        ];
    }

    /**
     * @return boolean
     */
    final public function hasAbility(string $ability): bool
    {
        switch ($ability)
        {
            case self::CREATE:
                return $this->canCreate;
            case self::DELETE:
                return $this->canDelete;
            case self::UPDATE:
                return $this->canUpdate;
            case self::VIEW:
                return $this->canView;
            default:
                return true;
        }
    }

    #endregion
}
