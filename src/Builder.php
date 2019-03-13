<?php

namespace TPG\Pcflib;

use TPG\Pcflib\Contracts\Arrayable;
use TPG\Pcflib\Contracts\Jsonable;

class Builder implements Arrayable, Jsonable
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

    /**
     * Output an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->offers()->toArray();
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
     * Generate an XML string
     * @return string
     */
    public function toXml()
    {
        $document = new \DOMDocument();
        $document->encoding = 'UTF-8';
        return $this->offers->toXml($document)->saveXML();
    }
}