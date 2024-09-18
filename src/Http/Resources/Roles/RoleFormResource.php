<?php

namespace Narsil\Policies\Http\Resources\Roles;

#region USE

use Illuminate\Http\Request;
use Narsil\Forms\Builder\AbstractFormNode;
use Narsil\Forms\Builder\Elements\FormCard;
use Narsil\Forms\Builder\Elements\FormTab;
use Narsil\Forms\Builder\Elements\FormTable;
use Narsil\Forms\Builder\Elements\FormTabs;
use Narsil\Forms\Builder\Inputs\FormNumber;
use Narsil\Forms\Builder\Inputs\FormString;
use Narsil\Forms\Builder\Inputs\FormTrans;
use Narsil\Forms\Http\Resources\AbstractFormResource;
use Narsil\Menus\Models\Menu;
use Narsil\Menus\Models\MenuHasNode;
use Narsil\Policies\Models\Role;
use Narsil\Tree\Http\Resources\NestedNodeResource;

#endregion

/**
 * @version 1.0.0
 *
 * @author Jonathan Rigaux
 */
class RoleFormResource extends AbstractFormResource
{
    #region CONSTRUCTOR

    /**
     * @param mixed $resource
     *
     * @return void
     */
    public function __construct(mixed $resource)
    {
        parent::__construct($resource, 'Role', 'role');
    }

    #endregion

    #region PUBLIC METHODS

    /**
     * @param Request $request
     *
     * @return array
     */
    public function toArray(Request $request): array
    {

        if (!$this->resource)
        {
            return [];
        }

        $attributes = parent::toArray($request);

        $nodes = $this->resource
            ->load(Menu::RELATIONSHIP_NODES . '.' . MenuHasNode::RELATIONSHIP_CHILDREN)
            ->{Menu::RELATIONSHIP_NODES}
            ->where(MenuHasNode::PARENT_ID, null);

        $attributes[Menu::RELATIONSHIP_NODES] = NestedNodeResource::collection($nodes);

        return $attributes;
    }

    #endregion

    #region PROTECTED METHODS

    /**
     * @return array<AbstractFormNode>
     */
    protected function getSchema(): array
    {
        return [
            (new FormTabs())
                ->children([
                    (new FormTab('Main'))
                        ->children([
                            (new FormCard())
                                ->children([
                                    (new FormString(Role::SLUG)),
                                    (new FormNumber(Role::LEVEL)),
                                    (new FormTrans(Role::LABEL)),

                                ]),
                        ]),
                    (new FormTab('Permissions'))
                        ->children([
                            (new FormCard())
                                ->children([
                                    (new FormTable(Role::RELATIONSHIP_PERMISSIONS)),
                                ]),
                        ]),
                ])


        ];
    }

    #endregion
}
