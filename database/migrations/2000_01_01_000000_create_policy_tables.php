<?php

#region USE

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Narsil\Policies\Enums\PermissionEnum;
use Narsil\Policies\Models\ModelHasPermission;
use Narsil\Policies\Models\ModelHasRole;
use Narsil\Policies\Models\Permission;
use Narsil\Policies\Models\Role;

#endregion

return new class extends Migration
{
    #region MIGRATIONS

    /**
     * @return void
     */
    public function up(): void
    {
        $this->createRolesTable();
        $this->createPermissionsTable();
        $this->createModelHasRolesTable();
        $this->createModelHasPermissionsTable();
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::drop(ModelHasPermission::TABLE);
        Schema::drop(ModelHasRole::TABLE);
        Schema::drop(Permission::TABLE);
        Schema::drop(Role::TABLE);
    }

    #endregion

    #region TABLES

    /**
     * @return void
     */
    private function createPermissionsTable(): void
    {
        Schema::create(Permission::TABLE, function (Blueprint $table)
        {
            $table->id();

            $table->boolean(Permission::ACTIVE)
                ->default(true);
            $table->string(Permission::NAME)
                ->unique();
            $table->string(Permission::TYPE)
                ->default(PermissionEnum::PAGE);

            $table->timestamps();
        });
    }

    /**
     * @return void
     */
    private function createRolesTable(): void
    {
        Schema::create(Role::TABLE, function (Blueprint $table)
        {
            $table->id();

            $table->boolean(Role::ACTIVE)
                ->default(true);
            $table->string(Role::NAME)
                ->unique();
            $table->integer(Role::LEVEL)
                ->default(0);
            $table->trans(Role::LABEL);

            $table->timestamps();
        });
    }

    /**
     * @return void
     */
    private function createModelHasPermissionsTable(): void
    {
        Schema::create(ModelHasPermission::TABLE, function (Blueprint $table)
        {
            $table->id();

            $table->morphs(ModelHasRole::RELATIONSHIP_MODEL);
            $table->foreignId(ModelHasPermission::PERMISSION_ID)
                ->constrained(Permission::TABLE, Permission::ID)
                ->cascadeOnDelete();
        });
    }

    /**
     * @return void
     */
    private function createModelHasRolesTable(): void
    {
        Schema::create(ModelHasRole::TABLE, function (Blueprint $table)
        {
            $table->id();

            $table->morphs(ModelHasRole::RELATIONSHIP_MODEL);
            $table->foreignId(ModelHasRole::ROLE_ID)
                ->constrained(Role::TABLE, Role::ID)
                ->cascadeOnDelete();
        });
    }

    #endregion
};
