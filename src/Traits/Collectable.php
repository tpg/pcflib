<?php

namespace TPG\Pcflib\Traits;

use TPG\Pcflib\OfferCollection;

/**
 * Trait Collectable
 * @package TPG\Pcflib\Traits
 */
trait Collectable
{
    /**
     * @var OfferCollection
     */
    protected $parent;

    /**
     * Set or get the parent collection
     *
     * @param OfferCollection|null $collection
     * @return OfferCollection|null
     */
    public function parent(OfferCollection $collection = null): ?OfferCollection
    {
        if ($collection) {
            $this->parent = $collection;
        }

        return $this->parent;
    }
}