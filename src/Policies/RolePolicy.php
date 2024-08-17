<?php

namespace Narsil\Policies\Policies;

#region USE

use Narsil\Policies\Models\Role;
use Narsil\Policies\Policies\AbstractPolicy;

#endregion

/**
 * @version 1.0.0
 *
 * @author Jonathan Rigaux
 */
final class RolePolicy extends AbstractPolicy
{
    #region CONSTRUCTOR

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct(Role::class);
    }

    #endregion
}