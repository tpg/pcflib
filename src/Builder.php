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
     * @param null $filename
     * @return string
     */
    public function toXml($filename = null)
    {
        $document = new \DOMDocument();

        $document->encoding = 'UTF-8';

        $xml = $this->offers->toXml($document);

        if ($filename) {

            $xml->save($filename);
        }

        return $xml->saveXML();
    }
}