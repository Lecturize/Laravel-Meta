<?php namespace vendocrat\Meta;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BaseCollection;

trait MetableTrait
{
	/**
	 * @var Collection
	 */
	protected $meta;

	/**
	 * True when the meta is loaded
	 *
	 * @var
	 */
	protected $metaLoaded = false;

	/**
	 * Load the meta data
	 *
	 * @return BaseCollection|null
	 */
	protected function loadMetaData()
	{
		if ( ! $this->metaLoaded ) {
			$this->setObserver();

			if ( $this->exists ) {

				$objects = $this->newMetaModel()
					->where( $this->getMetaKeyName(), $this->getKey() )
					->get();

				if ( ! is_null($objects) ) {
					$this->metaLoaded = true;
					return $this->meta = $objects->keyBy('meta_key');
				}
			}

			$this->metaLoaded = true;
			return $this->meta = new Collection();
		}

		return null;
	}

	/**
	 * Set a meta value by key
	 *
	 * @param $key
	 * @param $value
	 * @param bool $autosave
	 * @return bool
	 */
	public function setMeta( $key, $value = null, $autosave = true )
	{
		$this->loadMetaData();

		$setMeta = 'setMeta'. ucfirst(gettype($key));

		$set = $this->$setMeta( $key, $value );

		if ( $autosave ) {
			return $this->saveMeta();
		}

		return $set;
	}

	public function setMetaString( $key, $value )
	{
		$this->loadMetaData();

		if ( $this->meta->has($key) ) {
			$this->meta[$key]->meta_value = maybe_serialize($value);
		} else {
			$this->meta[$key] = $this->newMetaModel([
				'meta_key'   => strtolower($key),
				'meta_value' => maybe_serialize($value)
			]);
		}
	}

	protected function setMetaArray()
	{
		list($metas) = func_get_args();

		foreach ( $metas as $key => $value ) {
			$this->setMetaString($key, $value);
		}

		return $this->meta->sortByDesc('id')
			->take(sizeof($metas));
	}

	/**
	 * Get a meta value
	 *
	 * @param $key
	 * @param bool $raw
	 * @return null
	 */
	public function getMeta( $key, $raw = false )
	{
		$this->loadMetaData();

		$meta = $this->meta->get($key, null);

		if ( is_null($meta) || $meta->isDeleted() ) {
			return null;
		}

		return ($raw) ? $meta : maybe_unserialize($meta->meta_value);
	}

	/**
	 * Get all the meta data
	 *
	 * @param bool $raw
	 * @return BaseCollection
	 */
	public function getAllMeta( $raw = false )
	{
		$this->loadMetaData();

		$return = new BaseCollection();

		foreach ( $this->meta as $meta ) {
			if ( ! $meta->isDeleted() ) {
				$return->put( $meta->meta_key, ($raw ? $meta : maybe_unserialize($meta->meta_value) ) );
			}
		}

		return $return;
	}

	/**
	 * Remove a meta key
	 *
	 * @param $key
	 * @return bool
	 */
	public function hasMeta( $key )
	{
		$this->loadMetaData();

		if ( $this->meta->has($key) && $this->meta[$key]->meta_value ) {
			return true;
		}

		return false;
	}

	/**
	 * Remove a meta key
	 *
	 * @param $key
	 * @return $this
	 */
	public function deleteMeta( $key, $autosave = true )
	{
		$this->loadMetaData();

		$meta = $this->meta->get($key, null);

		if ( ! is_null($meta) ) {
			$meta->deleteOnSave();

			if ( $autosave ) {
				$this->saveMeta();
			}
		}
/*
		$this->metaLoaded = false;


		$meta = $this->meta->get($key, null);

		if ( ! is_null($meta) ) {
			if ( $autosave ) {
				$meta->forget();
			} else {
				$meta->deleteOnSave();
			}
		}
*/
	//	return $this->meta->forget($key);
	}

	/**
	 * Persist the meta data
	 */
	public function saveMeta()
	{
		$this->loadMetaData();

		foreach ( $this->meta as $meta ) {
			$meta->setTable($this->getMetaTable());
			if ( $meta->isDeleted() ) {
				$meta->delete();
			} elseif ($meta->isDirty()) {
				$meta->setAttribute( $this->getMetaKeyName(), $this->getKey() );
				$meta->save();
			}
		}
	}

	/**
	 * Returns a new meta model
	 *
	 * @param array $attributes
	 * @return Meta
	 */
	public function newMetaModel(array $attributes = array())
	{
		if ( isset($this->metaModel) && ! is_null($this->metaModel) ) {
			$class = $this->metaModel;
			$model = new $class($attributes);
		} else {
			$model = new Meta($attributes);
		}

		$model->setTable($this->getMetaTable());

		return $model;
	}

	/**
	 * Return the foreign key name for the meta table
	 *
	 * @return string
	 */
	protected function getMetaKeyName()
	{
		return 'metable_id';
		//	return isset($this->metaKeyName) ? $this->metaKeyName : $this->getForeignKey();
	}

	/**
	 * Return the table name
	 *
	 * @return null
	 */
	protected function getMetaTable()
	{
		return isset($this->metaTable) ? $this->metaTable : $this->getTable() .'_meta';
	}

	/**
	 * Return the meta as an array
	 *
	 * @return array
	 */
	public function metaToArray()
	{
		return $this->getAllMeta()->toArray();
	}

	/**
	 * Convert the model instance to an array.
	 *
	 * @return array
	 */
	public function toArray()
	{
		$attributes = $this->attributesToArray();
		$attributes = array_merge($attributes, $this->relationsToArray());
		return array_merge( $attributes, $this->metaToArray() );
	}

	/**
	 * Get an attribute from the model.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function getAttribute( $key )
	{
		if ( ($attr = parent::getAttribute($key)) !== null ) {
			return $attr;
		}

		return $this->getMeta($key);
	}

	/**
	 * Unset the data
	 *
	 * @param string $key
	 */
	/*
	public function __unset($key)
	{
		// unset attributes and relations
		parent::__unset($key);

		$this->unsetMeta($key);
	}
	*/

	/**
	 * Observe parent model saving
	 */
	protected function setObserver()
	{
		$this->saved(function ($model) {
			$model->saveMeta();
		});
	}

	public function scopeMeta( $query, $key, $value )
	{
		$this->loadMetaData();

		return $query->whereHas('meta', function($q) use($key, $value) {
			$q->where( 'meta_key', '=', strtolower($key) )
				->where( 'meta_value', '=', $value );
		});
	}
}