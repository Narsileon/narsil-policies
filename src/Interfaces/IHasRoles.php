<?php

namespace Narsil\Policies\Interfaces;

/**
 * @version 1.0.0
 *
 * @author Jonathan Rigaux
 */
interface IHasRoles
{
    #region CONSTANTS

    /**
     * @var string
     */
    final public const ATTRIBUTE_LEVEL = 'level';
    /**
     * @var string
     */
    final public const ATTRIBUTE_ROLE = 'role';

    /**
     * @var string
     */
    final public const RELATIONSHIP_ROLES = 'roles';

    #endregion
}
