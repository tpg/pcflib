<?php

namespace TPG\Pcflib;

class Builder
{
    /**
     * @var OfferCollection
     */
    protected $offers;

    /**
     * @return OfferCollection
     */
    public function offers(): OfferCollection
    {
        return $this->offers ?: $this->offers = new OfferCollection();
    }
}