<?php

namespace TPG\Pcflib\Categories;

use TPG\Pcflib\Contracts\Arrayable;
use TPG\Pcflib\Traits\HasAttributes;

/**
 * Class Category
 * @package TPG\Pcflib\Categories
 */
abstract class Category implements Arrayable
{
    use HasAttributes;
}