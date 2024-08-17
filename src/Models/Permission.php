<?php

namespace Narsil\Policies\Models;

#region USE

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Narsil\Policies\Enums\PermissionTypeEnum;

#endregion

/**
 * @version 1.0.0
 *
 * @author Jonathan Rigaux
 */
class Permission extends Model
{
    #region CONSTRUCTOR

    /**
     * @param array $attributes
     *
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->table = self::TABLE;

        $this->casts =  [
            self::ACTIVE => 'boolean',
            self::TYPE => PermissionTypeEnum::class,
        ];

        $this->fillable = [
            self::ACTIVE,
            self::NAME,
            self::TYPE,
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
    final public const NAME = 'name';
    /**
     * @var string
     */
    final public const TYPE = 'type';

    /**
     * @var string
     */
    final public const TABLE = 'permissions';

    #endregion

    #region SCOPES

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
                self::NAME,
                self::TYPE
            ])
            ->where(self::ACTIVE, true);
    }

    #endregion
}
