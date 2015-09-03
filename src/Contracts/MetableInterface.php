<?php namespace vendocrat\Meta\Contracts;

interface MetableInterface
{
	public function meta();
	public function getMeta( $key );
	public function setMeta( $key, $value );
	public function appendMeta( $key, $value );
	public function updateMeta( $key, $newValue, $oldValue = false );
	public function deleteMeta( $key, $value = false );
	public function deleteAllMeta();
	public function hasMeta( $key );
}