<?php

namespace Narsil\Policies\Models;

#region USE

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Narsil\Localization\Casts\TransAttribute;
use Narsil\Localization\Interfaces\IHasTranslations;
use Narsil\Localization\Traits\HasTranslations;
use Narsil\Policies\Enums\PermissionTypeEnum;
use Narsil\Policies\Interfaces\IHasPermissions;
use Narsil\Policies\Interfaces\IHasRoles;
use Narsil\Policies\Observers\RoleObserver;
use Narsil\Policies\Traits\HasPermissions;
use Narsil\Tables\Constants\Types;

#endregion

#[ObservedBy([RoleObserver::class])]
/**
 * @version 1.0.0
 *
 * @author Jonathan Rigaux
 */
class Role extends Model implements IHasPermissions, IHasTranslations
{
    use HasPermissions;
    use HasTranslations;

    #region CONSTRUCTOR

    /**
     * @param array $attributes
     *
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->table = self::TABLE;

        $this->casts = [
            self::ACTIVE => Types::BOOLEAN,
            self::LABEL => TransAttribute::class,
        ];

        $this->fillable = [
            self::ACTIVE,
            self::LABEL,
            self::LEVEL,
            self::SLUG,
        ];

        parent::__construct($attributes);
    }

    #endregion

    #region CONSTANTS

    /**
     * @var string
     */
    final public const ACTIVE = 'active';
    /**
     * @var string
     */
    final public const ID = 'id';
    /**
     * @var string
     */
    final public const LABEL = 'label';
    /**
     * @var string
     */
    final public const LEVEL = 'level';
    /**
     * @var string
     */
    final public const SLUG = 'slug';

    /**
     * @var string
     */
    final public const ATTRIBUTE_FUNCTIONS = 'functions';
    /**
     * @var string
     */
    final public const ATTRIBUTE_MODEL_HAS_ROLES_COUNT = 'model_has_roles_count';
    /**
     * @var string
     */
    final public const ATTRIBUTE_PAGES = 'pages';

    /**
     * @var string
     */
    final public const RELATIONSHIP_MODEL_HAS_ROLES = 'model_has_roles';
    /**
     * @var string
     */
    final public const RELATIONSHIP_USERS = 'users';

    /**
     * @var string
     */
    final public const TABLE = 'roles';

    #endregion

    #region ATTRIBUTES

    /**
     * @return array
     */
    final public function getFunctionsAttribute(): array
    {
        return collect($this->{self::RELATIONSHIP_PERMISSIONS})
            ->where(Permission::TYPE, '=', PermissionTypeEnum::FUNCTION)
            ->pluck(Permission::SLUG)
            ->toArray();
    }

    /**
     * @return array
     */
    final public function getPagesAttribute(): array
    {
        return collect($this->{self::RELATIONSHIP_PERMISSIONS})
            ->where(Permission::TYPE, '=', PermissionTypeEnum::PAGE)
            ->pluck(Permission::SLUG)
            ->toArray();
    }

    #endregion

    #region RELATIONSHIPS

    /**
     * @return HasMany
     */
    final public function model_has_roles(): HasMany
    {
        $modelHasRoles = $this->hasMany(
            ModelHasRole::class,
            ModelHasRole::ROLE_ID,
            ModelHasRole::ID,
        );

        return $modelHasRoles;
    }

    #endregion

    #region SCOPE

    /**
     * @param Builder $query
     *
     * @return void
     */
    final public function scopeOptions(Builder $query): void
    {
        $query
            ->select([
                self::ID,
                self::LABEL,
                self::SLUG,
            ])
            ->where(self::ACTIVE, true);
    }

    /**
     * @param Builder $query
     *
     * @return void
     */
    final public function scopeVisible(Builder $query): void
    {
        $level = Auth::user()?->{IHasRoles::ATTRIBUTE_LEVEL} ?? 0;

        $query->where(self::LEVEL, '<=', $level);
    }

    #endregion
}
