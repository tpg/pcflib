<?php

namespace TPG\Pcflib\Contracts;

/**
 * Interface Jsonable
 * @package TPG\Pcflib\Contracts
 */
interface Jsonable
{
    /**
     * Return a JSON encoded string.
     *
     * @param bool $pretty
     * @return string
     */
    public function toJson($pretty = false): string;
}