<?php

namespace TPG\Pcflib;

use TPG\Pcflib\Categories\Category;
use TPG\Pcflib\Contracts\Arrayable;
use TPG\Pcflib\Traits\Collectable;
use TPG\Pcflib\Traits\HasAttributes;

/**
 * Class Offer
 * @package TPG\Pcflib
 */
class Offer implements Arrayable
{
    use HasAttributes, Collectable;

    const CONTRACT_PERIOD_MONTHS = 'Months';
    const CONTRACT_PERIOD_WEEKS = 'Weeks';
    const CONTRACT_PERIOD_DAYS = 'Days';

    const AVAILABILITY_IN_STOCK = 'In Stock';
    const AVAILABILITY_OUT_OF_STOCK = 'Out of Stock';

    const ORDERED_FROM_SUPPLIER = -1;

    const UNIT_MEASURE_ML = 'ml';
    const UNIT_MEASURE_L = 'l';
    const UNIT_MEASURE_KL = 'kl';
    const UNIT_MEASURE_MM = 'mm';
    const UNIT_MEASURE_CM = 'cm';
    const UNIT_MEASURE_M = 'm';

    /**
     * The text fields that need to be wrapped in CDATA when exporting to XML.
     *
     * @var array
     */
    protected $textFields = [
        'Category',
        'ProductName',
        'Manufacturer',
        'ShopSKU',
        'ModelNumber',
        'Description',
        'ProductURL',
        'ImageURL',
        'Notes',
        'StockAvailability',
        'GroupID',
    ];

    /**
     * Second Hand product
     *
     * @var bool
     */
    protected $secondHand = false;

    /**
     * Set the category signature
     *
     * @param array $categories
     */
    protected function setCategorySignature(array $categories)
    {
        $this->attributes['Category'] = implode(' > ', $categories);
    }

    /**
     * Offer constructor.
     *
     * @param array $categorySignature
     * @param Category|null $categoryAttributes
     */
    public function __construct(array $categorySignature = null, Category $categoryAttributes = null)
    {
        $this->requiredAttributes = [
            'Category',
            'Price',
            'ProductName',
            'Manufacturer',
            'ShopSKU',
        ];

        $this->attributes['Category'] = null;

        if ($categorySignature) {

            $this->setCategorySignature($categorySignature);
        }

        $this->attributes['Attributes'] = $categoryAttributes ? $categoryAttributes->toArray() : null;
    }

    /**
     * Fill with attributes
     *
     * @param array $attributes
     * @return $this
     */
    public function fill(array $attributes)
    {
        $this->attributes = array_map(function ($attributes) {
            return strip_tags($attributes);
        }, $attributes);
        return $this;
    }

    /**
     * Set category details
     *
     * @param array $signature
     * @param Category $attributes
     * @return $this
     */
    public function category(array $signature, Category $attributes = null): Offer
    {
        $this->setCategorySignature($signature);

        $this->attributes['Attributes'] = $attributes ? $attributes->toArray() : (
            $this->attributes['Attributes'] ?? null
        );

        return $this;
    }

    /**
     * Set product name
     *
     * @param string $name
     * @return Offer
     */
    public function name(string $name): Offer
    {
        $this->attributes['ProductName'] = strip_tags($name);

        return $this;
    }

    /**
     * Set manufacturer
     *
     * @param string $manufacturer
     * @return Offer
     */
    public function manufacturer(string $manufacturer): Offer
    {
        $this->attributes['Manufacturer'] = strip_tags($manufacturer);

        return $this;
    }

    /**
     * Set shop SKU
     *
     * @param string $sku
     * @return Offer
     */
    public function sku(string $sku): Offer
    {
        $this->attributes['ShopSKU'] = strip_tags($sku);
        return $this;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Offer
     */
    public function description(string $description): Offer
    {
        $this->attributes['Description'] = strip_tags($description);

        return $this;
    }

    /**
     * Set EAN
     *
     * @param string $ean
     * @return Offer
     */
    public function ean(string $ean): Offer
    {
        $this->attributes['EAN'] = strip_tags($ean);
        return $this;
    }

    /**
     * Set UPC
     *
     * @param string $upc
     * @return Offer
     */
    public function upc(string $upc): Offer
    {
        $this->attributes['UPC'] = strip_tags($upc);
        return $this;
    }

    /**
     * Set the product pricing
     *
     * @param float $price
     * @param float|null $salePrice
     * @param float|null $deliveryCost
     * @return Offer
     */
    public function price(float $price, float $salePrice = null, float $deliveryCost = null): Offer
    {
        $this->attributes['Price'] = $price;

        if ($salePrice) {
            $this->salesPrice($salePrice);
        }

        if ($deliveryCost) {
            $this->deliveryCost($deliveryCost);
        }

        return $this;
    }

    /**
     * Set the sales price
     *
     * @param float $salePrice
     * @return Offer
     */
    public function salesPrice(float $salePrice): Offer
    {
        $this->attributes['SalePrice'] = $salePrice;
        return $this;
    }

    /**
     * Set the delivery cost
     *
     * @param float $deliveryCost
     * @return Offer
     */
    public function deliveryCost(float $deliveryCost): Offer
    {
        $this->attributes['DeliveryCost'] = $deliveryCost;
        return $this;
    }

    /**
     * @param float $cash
     * @param int $periodLength
     * @param string $periodType
     * @return Offer
     */
    public function contractPricing(float $cash, int $periodLength, string $periodType = self::CONTRACT_PERIOD_MONTHS): Offer
    {
        $this->attributes['ContractPricing'] = [
            'CashComponent' => $cash,
            'PeriodLength' => $periodLength,
            'PeriodType' => strip_tags($periodType)
        ];
        return $this;
    }

    /**
     * Set model number
     *
     * @param string $modelNumber
     * @return Offer
     */
    public function modelNumber(string $modelNumber): Offer
    {
        $this->attributes['ModelNumber'] = strip_tags($modelNumber);
        return $this;
    }

    /**
     * Set stock availabilities
     *
     * @param string|null $stockAvailability
     * @param int|null $stockLevel
     * @return Offer
     */
    public function availability(string $stockAvailability = null, int $stockLevel = null)
    {
        $this->attributes['StockAvailability'] = $stockAvailability;
        $this->attributes['StockLevel'] = $stockLevel;
        return $this;
    }

    /**
     * Set the product URL
     *
     * @param string $url
     * @return Offer
     */
    public function productUrl(string $url): Offer
    {
        $this->attributes['ProductURL'] = strip_tags($url);
        return $this;
    }

    /**
     * Set the product image URL
     *
     * @param string $url
     * @return Offer
     */
    public function imageUrl(string $url): Offer
    {
        $this->attributes['ImageURL'] = strip_tags($url);
        return $this;
    }

    /**
     * Set a product as second hand
     *
     * @param bool $secondHand
     * @return Offer
     */
    public function secondHand($secondHand = true): Offer
    {
        $this->secondHand = $secondHand;
        return $this;
    }

    /**
     * Set the product notes
     *
     * @param string $notes
     * @return Offer
     */
    public function notes(string $notes): Offer
    {
        $this->attributes['Notes'] = strip_tags($notes) . ($this->secondHand ? ' SecondHand' : null);
        return $this;
    }

    /**
     * Set this offer on the marketplace
     *
     * @param bool $marketplace
     * @return Offer
     */
    public function marketplace($marketplace = true): Offer
    {
        $this->attributes['Marketplace'] = $marketplace ? '1' : null;
        return $this;
    }

    /**
     * @param bool $bundle
     * @return Offer
     */
    public function bundle($bundle = true): Offer
    {
        $this->attributes['Bundle'] = $bundle ? '1' : null;
        return $this;
    }

    /**
     * @param $groupId
     * @return Offer
     */
    public function groupId($groupId): Offer
    {
        $this->attributes['GroupID'] = $groupId;
        return $this;
    }

    /**
     * Set the number of units and their size
     *
     * @param int $count
     * @param int $unit
     * @param string $measure
     * @return $this
     */
    public function units(int $count, int $unit, string $measure)
    {
        $this->attributes['NoOfUnits'] = $count;

        $this->attributes['UnitMeasure'] = [
            'Unit' => $unit,
            'Measure' => strip_tags($measure)
        ];
        return $this;
    }

    /**
     * Delete this Offer from the parent collection
     */
    public function delete()
    {
        if ($this->parent && array_key_exists('ShopSKU', $this->attributes)) {

            $this->parent->delete($this->attributes['ShopSKU']);
        }
    }

    /**
     * Output an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_filter($this->attributes, function ($attribute) {
            return $attribute ? true : false;
        });
    }

    /**
     * Add attributes to the XML node
     *
     * @param \DOMNode $node
     * @param array|null $attributes
     * @return void
     */
    protected function addAttributesToXmlElement(\DOMNode $node, array $attributes = null)
    {
        foreach ($attributes as $key => $value) {

            if ($value) {

                $offerNode = new \DOMElement($key);

                $node->appendChild($offerNode);

                if (is_array($value)) {
                    $this->addAttributesToXmlElement($offerNode, $value);
                } else {

                    if (in_array($key, $this->textFields)) {

                        $value = $node->ownerDocument->createCDATASection($value);
                        $offerNode->appendChild($value);
                    } else {

                        $offerNode->textContent = $value;
                    }

                    $node->appendChild($offerNode);
                }
            }
        }
    }

    /**
     * @param \DOMNode $node
     * @return void
     * @throws Exceptions\MissingRequiredAttribute
     */
    public function toXmlNode(\DOMNode $node) {
        $this->verifyAttributes();
        $this->addAttributesToXmlElement($node, $this->attributes);
    }
}
