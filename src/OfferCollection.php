<?php

namespace TPG\Pcflib;

use TPG\Pcflib\Contracts\Arrayable;
use TPG\Pcflib\Contracts\Jsonable;
use TPG\Pcflib\Traits\ArrayAccessible;

/**
 * Class OfferCollection
 * @package TPG\Pcflib
 */
class OfferCollection implements \Countable, Arrayable, \ArrayAccess, Jsonable
{
    use ArrayAccessible;

    /**
     * @var Offer[]
     */
    protected $items = [];

    /**
     * OfferCollection constructor.
     * @param array $offers
     */
    public function __construct(array $offers = [])
    {
        $this->items = $offers;
    }

    /**
     * Add an offer to the collection
     *
     * @param mixed $offers
     * @return OfferCollection
     */
    public function add($offers): OfferCollection
    {
        if (is_array($offers)) {
            $this->items = array_merge($this->items, $offers);
            return $this;
        }
        $this->items[] = $offers;
        return $this;
    }

    /**
     * Count the number of offers
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Find an offer by the Shop SKU
     *
     * @param $sku
     * @return Offer|null
     */
    public function find($sku): ?Offer
    {
        $offers = array_filter($this->items, function (Offer $offer) use ($sku) {
            return $offer->toArray()['ShopSKU'] === (string)$sku;
        });

        if (count($offers) >= 1) {
            $offer = $offers[0];
            $offer->parent($this);
            return $offer;
        }

        return null;
    }

    /**
     * Clone an Offer object
     *
     * @param $sku
     * @return Offer|null
     */
    public function clone($sku): ?Offer
    {
        $offer = $this->find($sku);
        if ($offer) {
            return (new Offer())->fill($offer->toArray());
        }
    }

    /**
     * Remove an offer from the collection
     *
     * @param $sku
     */
    public function delete($sku)
    {
        $offer = $this->find($sku);
        $index = array_search($offer, $this->items);
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    /**
     * Clear the collection
     *
     * @return $this
     */
    public function purge()
    {
        $this->items = [];
        return $this;
    }

    /**
     * Output an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'Offers' => array_map(function (Offer $offer) {
                return $offer->toArray();
            }, $this->items)
        ];
    }

    /**
     * Return a JSON encoded string.
     *
     * @param bool $pretty
     * @return string
     */
    public function toJson($pretty = false): string
    {
        return json_encode($this->toArray(), JSON_NUMERIC_CHECK + ($pretty ? JSON_PRETTY_PRINT : 0));
    }

    /**
     * Generate XML from the offer collection
     *
     * @param \DOMDocument $document
     * @return \DOMDocument
     * @throws Exceptions\MissingRequiredAttribute
     */
    public function toXml(\DOMDocument $document)
    {
        $element = $document->createElement('Offers');
        foreach ($this->items as $offer) {
            $offerElenent = $element->appendChild(new \DOMElement('Offer'));
            $document->appendChild($element);
            $offer->toXmlNode($offerElenent);
        }
        return $document;
    }
}
