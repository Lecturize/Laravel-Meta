<?php namespace Lecturize\Meta\Models;

use Lecturize\Meta\MetableUtils;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meta extends Model
{
	use SoftDeletes;

	/**
     * @todo make this editable via config file
     * @inheritdoc
	 */
	protected $table = 'meta';

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
	protected $dataTypes = ['boolean', 'integer', 'double', 'float', 'string', 'NULL'];

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
	public function setValueAttribute( $value )
	{
		if ( is_array($value) || is_object($value) ) {
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
	public function getValueAttribute( $value )
	{
		return MetableUtils::maybe_unserialize( $value );
	}
}