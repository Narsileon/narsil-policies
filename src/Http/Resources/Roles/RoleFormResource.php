<?php

namespace Narsil\Policies\Http\Resources\Roles;

#region USE

use Narsil\Forms\Builder\AbstractFormNode;
use Narsil\Forms\Builder\Elements\FormCard;
use Narsil\Forms\Builder\Elements\FormTab;
use Narsil\Forms\Builder\Elements\FormTable;
use Narsil\Forms\Builder\Elements\FormTabs;
use Narsil\Forms\Builder\Inputs\FormNumber;
use Narsil\Forms\Builder\Inputs\FormString;
use Narsil\Forms\Builder\Inputs\FormTrans;
use Narsil\Forms\Http\Resources\AbstractFormResource;
use Narsil\Policies\Models\Role;

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

    #region PROTECTED METHODS

    /**
     * @return array<AbstractFormNode>
     */
    protected function getSchema(): array
    {
        return [
            (new FormTabs())
                ->children([
                    (new FormTab('main'))
                        ->label('Main')
                        ->children([
                            (new FormCard())
                                ->children([
                                    (new FormString(Role::SLUG)),
                                    (new FormTrans(Role::LABEL)),
                                    (new FormNumber(Role::LEVEL)),
                                ]),
                        ]),
                    (new FormTab('permissions'))
                        ->label('Permissions')
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
