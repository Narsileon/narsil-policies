<?php

namespace Narsil\Policies\Http\Menus;

#region USE

use Narsil\Menus\Enums\VisibilityEnum;
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
            MenuNode::VISIBILITY => VisibilityEnum::AUTH->value,
            MenuNode::RELATIONSHIP_ICON => 'lucide/user-cog',
        ]];
    }

    #endregion
}
