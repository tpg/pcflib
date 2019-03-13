<?php

namespace TPG\Pcflib;

use TPG\Pcflib\Categories\Category;
use TPG\Pcflib\Contracts\Arrayable;
use TPG\Pcflib\Traits\HasAttributes;

/**
 * Class Offer
 * @package TPG\Pcflib
 */
class Offer implements Arrayable
{
    use HasAttributes;

    const CONTRACT_PERIOD_MONTHS = 'Months';
    const CONTRACT_PERIOD_WEEKS = 'Weeks';
    const CONTRACT_PERIOD_DAYS = 'Days';

    const AVAILABILITY_IN_STOCK = 'In Stock';
    const AVAILABILITY_OUT_OF_STOCK = 'Out of Stock';

    /**
     * @var Category
     */
    protected $categoryAttributes;

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
        $this->attributes = $attributes;
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
        $this->attributes['Name'] = $name;

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
        $this->attributes['Manufacturer'] = $manufacturer;

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
        $this->attributes['ShopSKU'] = $sku;
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
        $this->attributes['Description'] = $description;

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
        $this->attributes['EAN'] = $ean;
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
        $this->attributes['UPC'] = $upc;
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
        $this->attributes['SalePrice'] = $salePrice;
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
            'PeriodType' => $periodType
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
        $this->attributes['ModelNumber'] = $modelNumber;
        return $this;
    }

    /**
     * Set stock availabilities
     *
     * @param int|null $stockAvailability
     * @param int|null $stockLevel
     * @return Offer
     */
    public function availability(int $stockAvailability = null, int $stockLevel = null)
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
        $this->attributes['ProductURL'] = $url;
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
        $this->attributes['ImageURL'] = $url;
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
        $this->attributes['Notes'] = $notes . ($this->secondHand ? ' SecondHand' : null);
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
        $this->attributes['Marketplace'] = $marketplace;
        return $this;
    }

    /**
     * @param bool $bundle
     * @return Offer
     */
    public function bundle($bundle = true): Offer
    {
        $this->attributes['Bundle'] = $bundle;
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
     * @param int $unit
     * @param string $measure
     * @return $this
     */
    public function unitMeasure(int $unit, string $measure)
    {
        $this->attributes['UnitMeasure'] = [
            'Unit' => $unit,
            'Measure' => $measure
        ];
        return $this;
    }

    /**
     * @param int $count
     * @return $this
     */
    public function unitCount(int $count)
    {
        $this->attributes['NoOfUnits'] = $count;
        return $this;
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
     * @param null $attributes
     * @return void
     */
    protected function addAttributesToXmlElement(\DOMNode $node, $attributes = null)
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
     */
    public function toXmlNode(\DOMNode $node) {
        $this->addAttributesToXmlElement($node, $this->attributes);
    }
}
