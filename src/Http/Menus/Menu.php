<?php

namespace Narsil\Menus\Http\Menus;

#region USE

use Narsil\Menus\Http\Menus\AbstractMenu;
use Narsil\Menus\Models\MenuNode;

#endregion

/**
 * @version 1.0.0
 *
 * @author Jonathan Rigaux
 */
class Menu extends AbstractMenu
{
    #region PUBLIC METHODS

    /**
     * @return array
     */
    public static function getBackendMenu(): array
    {
        return [[
            MenuNode::LABEL => 'Roles',
            MenuNode::URL => '/backend/roles',
            MenuNode::RELATIONSHIP_ICON => 'lucide/user-cog',
        ]];
    }

    #endregion
}
