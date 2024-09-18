<?php

namespace Narsil\Policies\Http\Resources\Permissions;

#region USE

use Narsil\Forms\Builder\AbstractFormNode;
use Narsil\Forms\Builder\Elements\FormCard;
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
class PermissionFormResource extends AbstractFormResource
{
    #region CONSTRUCTOR

    /**
     * @param mixed $resource
     *
     * @return void
     */
    public function __construct(mixed $resource)
    {
        parent::__construct($resource, 'Permission', 'permission');
    }

    #endregion

    #region PROTECTED METHODS

    /**
     * @return array<AbstractFormNode>
     */
    protected function getSchema(): array
    {
        return [
            (new FormCard())
                ->children([
                    (new FormString(Role::SLUG))
                        ->readOnly(),
                    (new FormTrans(Role::LABEL)),
                ]),
        ];
    }

    #endregion
}
