<?php

namespace Narsil\Policies\Models;

#region USE

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#endregion

/**
 * @version 1.0.0
 *
 * @author Jonathan Rigaux
 */
class ModelHasPermission extends Model
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

        $this->fillable = [
            self::MODEL_ID,
            self::MODEL_TYPE,
            self::PERMISSION_ID,
        ];

        parent::__construct($attributes);
    }

    #endregion

    #region CONSTANTS

    /**
     * @var string
     */
    final public const ID = 'id';
    /**
     * @var string
     */
    final public const MODEL_ID = 'model_id';
    /**
     * @var string
     */
    final public const MODEL_TYPE = 'model_type';
    /**
     * @var string
     */
    final public const PERMISSION_ID = 'permission_id';

    /**
     * @var string
     */
    final public const RELATIONSHIP_MODEL = 'model';
    /**
     * @var string
     */
    final public const RELATIONSHIP_PERMISSION = 'permission';

    /**
     * @var string
     */
    final public const TABLE = 'model_has_permissions';

    #endregion

    #region RELATIONSHIPS

    /**
     * @return MorphTo
     */
    final public function model(): MorphTo
    {
        return $this->morphTo(
            self::RELATIONSHIP_MODEL,
            self::MODEL_TYPE,
            self::MODEL_ID
        );
    }

    /**
     * @return BelongsTo
     */
    final public function role(): BelongsTo
    {
        return $this->belongsTo(
            Permission::class,
            self::PERMISSION_ID,
            Permission::ID,
        );
    }

    #endregion
}
