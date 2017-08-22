<?php namespace Lecturize\Meta\Contracts;

interface MetableInterface
{
    /**
     * @return mixed
     */
    public function meta();

    /**
     * @return mixed
     */
    public function getMeta( $key );

    /**
     * @return mixed
     */
    public function setMeta( $key, $value );

    /**
     * @return mixed
     */
    public function appendMeta( $key, $value );

    /**
     * @return mixed
     */
    public function updateMeta( $key, $newValue, $oldValue = false );

    /**
     * @return mixed
     */
    public function deleteMeta( $key, $value = false );

    /**
     * @return mixed
     */
    public function deleteAllMeta();

    /**
     * @return mixed
     */
    public function hasMeta( $key );
}