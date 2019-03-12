<?php

namespace TPG\Pcflib;

/**
 * Class OfferCollection
 * @package TPG\Pcflib
 */
class OfferCollection implements \Countable
{
    /**
     * @var array
     */
    protected $offers = [];

    /**
     * OfferCollection constructor.
     * @param array $offers
     */
    public function __construct(array $offers = [])
    {
        $this->offers = $offers;
    }

    /**
     * Add an offer to the collection
     *
     * @param Offer $offer
     */
    public function add(Offer $offer)
    {
        $this->offers[] = $offer;
    }

    /**
     * Count the number of offers
     *
     * @return int
     */
    public function count()
    {
        return count($this->offers);
    }

    /**
     * Find an offer by the Shop SKU
     *
     * @param $sku
     * @return Offer|null
     */
    public function find($sku): ?Offer
    {
        $offers = array_filter($this->offers, function (Offer $offer) use ($sku) {
            return $offer->toArray()['ShopSKU'] === (string)$sku;
        });

        if (count($offers) >= 1) {
            return $offers[0];
        }

        return null;
    }

    public function clone($sku): ?Offer
    {
        $offer = $this->find($sku);
        if ($offer) {
            return (new Offer())->fill($offer->toArray());
        }
    }
}