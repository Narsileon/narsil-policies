<?php

namespace Narsil\Policies\Commands;

#region USE

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Narsil\Policies\Constants\PoliciesConfig;
use Narsil\Policies\Enums\PermissionTypeEnum;
use Narsil\Policies\Models\Permission;
use Narsil\Policies\Services\PoliciesService;

#endregion

/**
 * @version 1.0.0
 *
 * @author Jonathan Rigaux
 */
class SyncPermissionsCommand extends Command
{
    #region CONSTRUCTOR

    /**
     * @return void
     */
    public function __construct()
    {
        $this->signature = 'narsil:sync-permissions';
        $this->description = 'Syncs the permissions table with the model policies';

        parent::__construct();
    }

    #endregion

    #region PUBLIC METHODS

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->createFunctionPermissions();
        $this->createModelPermissions();
        $this->createPagePermissions();

        $this->info('Permissions table has been successfully synced with the model policies.');
    }

    #endregion

    #region PRIVATE METHODS

    /**
     * @return void
     */
    private function createFunctionPermissions(): void
    {
        $permissions = Config::get(PoliciesConfig::FUNCTIONS, []);

        foreach ($permissions as $permission)
        {
            Permission::firstOrCreate([
                Permission::SLUG => $permission,
                Permission::TYPE => PermissionTypeEnum::FUNCTION->value,
            ]);
        }
    }

    /**
     * @param string $modelClass
     * @param string $ability
     *
     * @return void
     */
    private function createModelPermission(string $modelClass, string $ability): void
    {
        $permission = PoliciesService::getPermissionName($modelClass, $ability);

        Permission::firstOrCreate([
            Permission::SLUG => $permission,
            Permission::TYPE => PermissionTypeEnum::PAGE->value,
        ]);
    }

    /**
     * @return void
     */
    private function createModelPermissions(): void
    {
        $policies = Gate::policies();

        foreach ($policies as $model => $policy)
        {
            if (!class_exists($policy))
            {
                continue;
            }

            $policyInstance = new $policy();

            if ($policyInstance->canView)
            {
                $this->createModelPermission($model, 'view');
            }
            if ($policyInstance->canCreate)
            {
                $this->createModelPermission($model, 'create');
            }
            if ($policyInstance->canUpdate)
            {
                $this->createModelPermission($model, 'update');
            }
            if ($policyInstance->canDelete)
            {
                $this->createModelPermission($model, 'delete');
            }
        }
    }

    /**
     * @return void
     */
    private function createPagePermissions(): void
    {
        $permissions = Config::get(PoliciesConfig::PAGES, []);

        foreach ($permissions as $permission)
        {
            Permission::firstOrCreate([
                Permission::SLUG => $permission,
                Permission::TYPE => PermissionTypeEnum::PAGE->value,
            ]);
        }
    }

    #endregion
}
