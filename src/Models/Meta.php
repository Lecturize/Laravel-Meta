<?php namespace vendocrat\Meta\Models;

use vendocrat\Meta\MetableUtils;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meta extends Model
{
	use SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'meta';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'metable_id',
		'metable_type',
		'key',
		'value'
	];

	/**
	 * Available datatypes
	 *
	 * @var array
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
	 * @return mixed
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