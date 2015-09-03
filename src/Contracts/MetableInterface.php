<?php namespace vendocrat\Meta\Contracts;

interface MetableInterface
{
	public function meta();
	public function getMeta( $key );
	public function setMeta( $key, $value = null, $autosave = true );
}