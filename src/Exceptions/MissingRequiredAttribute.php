<?php

namespace TPG\Pcflib\Exceptions;

class MissingRequiredAttribute extends \Exception
{

    /**
     * MissingRequiredAttribute constructor.
     */
    public function __construct(string $attribute)
    {
        $message = 'Required attribute ' . $attribute . ' is missing';

        parent::__construct($message);
    }
}