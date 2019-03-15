<?php

namespace TPG\Pcflib\Traits;

trait ArrayAccessible
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * Check if an offset exists
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * Offset to retrieve
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * Offset to set
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    /**
     * Offset to unset
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }
}