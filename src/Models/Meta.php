<?php namespace vendocrat\Meta\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
	/**
	 * @var array
	 */
	protected $fillable = [
		'key',
		'value'
	];

	/**
	 * @var array
	 */
	protected $dataTypes = ['boolean', 'integer', 'double', 'float', 'string', 'NULL'];

	/**
	 * Whether or not to delete the Data on save
	 *
	 * @var bool
	 */
	protected $deleteOnSave = false;

	/**
	 * Whether or not to delete the Data on save
	 *
	 * @param bool $bool
	 */
	public function deleteOnSave($bool = true)
	{
		$this->deleteOnSave = $bool;
	}

	/**
	 * Check if the model needs to be deleted
	 *
	 * @return bool
	 */
	public function isDeleted()
	{
		return (bool) $this->deleteOnSave;
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
			$this->attributes['meta_value'] = serialize($value);
		} else {
			$this->attributes['meta_value'] = $value;
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
		return $this->maybe_unserialize( $value );
	}

	/**
	 * Unserialize value only if it was serialized.
	 *
	 * @param string $original Maybe unserialized original, if is needed.
	 * @return mixed Unserialized data can be any type.
	 */
	private function maybe_unserialize( $original ) {
		if ( $this->is_serialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
			return @unserialize( $original );
		return $original;
	}

	/**
	 * Check value to find if it was serialized.
	 *
	 * If $data is not an string, then returned value will always be false.
	 * Serialized data is always a string.
	 *
	 * @param string $data   Value to check to see if was serialized.
	 * @param bool   $strict Optional. Whether to be strict about the end of the string. Default true.
	 * @return bool False if not serialized and true if it was.
	 */
	private function is_serialized( $data, $strict = true ) {
		// if it isn't a string, it isn't serialized.
		if ( ! is_string( $data ) ) {
			return false;
		}
		$data = trim( $data );
		if ( 'N;' == $data ) {
			return true;
		}
		if ( strlen( $data ) < 4 ) {
			return false;
		}
		if ( ':' !== $data[1] ) {
			return false;
		}
		if ( $strict ) {
			$lastc = substr( $data, -1 );
			if ( ';' !== $lastc && '}' !== $lastc ) {
				return false;
			}
		} else {
			$semicolon = strpos( $data, ';' );
			$brace     = strpos( $data, '}' );
			// Either ; or } must exist.
			if ( false === $semicolon && false === $brace )
				return false;
			// But neither must be in the first X characters.
			if ( false !== $semicolon && $semicolon < 3 )
				return false;
			if ( false !== $brace && $brace < 4 )
				return false;
		}
		$token = $data[0];
		switch ( $token ) {
			case 's' :
				if ( $strict ) {
					if ( '"' !== substr( $data, -2, 1 ) ) {
						return false;
					}
				} elseif ( false === strpos( $data, '"' ) ) {
					return false;
				}
			// or else fall through
			case 'a' :
			case 'O' :
				return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
			case 'b' :
			case 'i' :
			case 'd' :
				$end = $strict ? '$' : '';
				return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
		}
		return false;
	}
}