<?php

namespace TPG\Pcflib\Contracts;

interface Arrayable
{
    /**
     * Output an array
     *
     * @return array
     */
    public function toArray(): array;
}