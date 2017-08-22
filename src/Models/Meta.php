<?php namespace Lecturize\Meta\Models;

use Lecturize\Meta\MetableUtils;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Meta
 * @package Lecturize\Meta\Models
 */
class Meta extends Model
{
    use SoftDeletes;

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'metable_id',
        'metable_type',
        'key',
        'value'
    ];

    /**
     * @inheritdoc
     */
    protected $dates = ['deleted_at'];

    /**
     * @inheritdoc
     */
    protected $dataTypes = ['boolean', 'integer', 'double', 'float', 'string', 'NULL'];

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();

        $this->table = config('lecturize.meta.table', 'meta');
    }

    /**
     * Morph metables
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function metable()
    {
        return $this->morphTo();
    }

    /**
     * Set the value attribute
     *
     * @param $value
     */
    public function setValueAttribute($value)
    {
        if (is_array($value) || is_object($value)) {
            $this->attributes['value'] = serialize($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }

    /**
     * Get the value attribute
     *
     * @param $value
     * @return mixed
     */
    public function getValueAttribute($value)
    {
        return MetableUtils::maybe_unserialize($value);
    }
}