# PCFlib
__Price Check Feed XML Library__

The PCFLib is an XML generator for the PriceCheck Offers XML Feed. The library is designed to help generate the XML needed so that PriceCheck can source product information from your website.

## Installation

PCFLib can be installed through Composer:

```
composer require tpg/pcflib
```

## Usage

You'll need to `require` the Composer autoloader if you're not using a framework that does so automatically.

The Price Check feed XML is constructed using the a `Builder` instance. The methods on the `Builder` class are chainable. Some methods take additional settings. In most cases TCFlib will provide an additional class to pass these settings in.

```php
require __DIR__.'/vendor/autoload.php';

$feed = new TPG\Pcflib\Builder();
//...
```

## Generating the XML

At any point you can get a copy of the XML by calling the `toXml()` method on the `Builder` instance:

```php
$xml = $feed->toXml();

// You can save it directly to a file as well by passing in a pathname:
$feed->toXml('pcf_feed.xml');
```

Calling the `toXml()` method does not alter the instance in anyway so you can generate XML and still continue to build up offers afterwards.

## Offers

You build up a Price Check feed by adding offers to the builder. The class provides a simple API for working with offers. Accessing offers is done through the `offers()` method.

### Creating offers

A single offer is represented by an instance of the `Offer` class. This class contains all the information for a single offer. You can create instances by adding creating an instance of `Offer` and passing it to the `add()` method:

```php
// You can 
$feed->offers()->add((new TPG\Pcflib\Offer())->...);
```

#### Setting the category

Price Check has a large catalog of products that are organized into categories. You can set the category by passing an array of category items to the `setCategory()` method. The array MUST be flat and contain only the string category names and they must appear in the correct order. For example, if you are added a book to the `Autobiographies` categry, you could:

```php
$offer->setCategory(['Books', 'Non-fiction', 'Autobiographies']);
```

It is important that the category names are correct. Check the website is you're unsure.

The `setCategory` method also takes a second argument which must be an instance of `Category` class. This is only required for categories that have additional attributes that need to be set. These categories are currently `Books`, `Videos`, `Clothing`, `Wine` and `Music`. Pcflib provides classes that extend `Category` for each of these:

```php
$offers->setCategory(['Books', 'Non-fiction', 'Autobiographies'],
    (new TPC\Pcflib\Categories\Book)
        ->setFormat(TPC\Pcflib\Categories\Book::FORMAT_HARDCOVER)
        ->setIsbn('1234-5678-9012-3456')
        ->setAuthor('Job Rumble');
)
```

For books, the Format, and ISBN numbers are required. For Videos, you must specify the Format, for Clothing you must specify the Age Group, Colour, Gender and Size of the item, and for Music, you must specify the Format. There are a bunch of optional attributes each of those categories as well.

Missing a required attribute will throw a `MissingCategoryAttribute` exception.

#### Setting product details

You'll want to specify some more product specific details which can be done through the `setProduct` method. You MUST provide a name, manufacturer, description and SKU number. You can also provide a model number, EAN barcode and UPC code.

```php
$offer->setProduct((new TPC\Pcflib\Product())
    ->setName('In Black and White: The Jake White Story')
    ->setManufacturer('Zebra Press')
    ->setDescription('In Black and White traces the life story of Springbok rugby coach Jake White, right up to and including the 2007 Rugby World Cup. [...] White's story will both absorb and astound.')
    ->setSku(12)
    ->setEan('60033254123123');
)
```

#### Setting price details

All products must have at least a price. However, you can also set a sale price and include delivery pricing as well. Note that Price Check insist that the delivery price is for that product ALONE and without any additional products.

You can set pricing information using the `setPrice()`, `setSalePrice()` and `setDeliveryPrice()` methods.

```php
$product
    ->setPrice(151,96)
    ->setSalePrice(139)
    ->setDeliveryPrice(45)
);
```

#### Contract pricing

Price check also provides support for contract pricing. You can set this using the `setContract()` method:

```php
$product->setContract(600, 24, TPG\Pcflib\Product::CONTRACT_PERIOD_MONTHS);
```

The first parameter is the cash component, the second parameter is the period length and the last parameter is the period type which can be one of:

 - `CONTRACT_PERIOD_MONTHS`
 - `CONTRACT_PERIOD_WEEKS`
 - `CONTRACT_PERIOD_DAYS`

#### Setting product URLs

The links for the products can be set on the offer directly using the `setProductUrl` and `setImageUrl` methods:

```php
$product->setProductUrl('http://www.example.com/showproduct.php?product_id=21');
$product->setImageUrl('http://www.example.com/showimage.php?product_id=21');
```

#### Marking an offer as Second Hand

Sometimes you may want to mark a product as "used" or "second hard". You can do so by calling the `secondHand()` method. If you need to _unset_ the used status of a product, pass a boolean "false" as the only parameter:

```php
$product->secondHand();

// or...

$product->secondHand(false);
```

#### Setting stock availability

You can also pass in stock availability details. If a product is in stock, you can call the `inStock` method and optionally pass in a boolean "true". For out of stock products, pass a boolean "false".

When setting a product as in stock, you can also specify the number of items on hand by passing an integer to `setStockLevel()`:

```php
$product
    ->inStock(true)
    ->setStockLevel(100);
```

#### Additional setters

There are a few additional, optional setter that can be used to define your product:

```php
$product
    // Price Check Marketplace
    ->setMarketplace(false)
    
    // If the product is a bundle
    ->setBundle(false)
    
    // To group products together
    ->setGroupId('IPHONE_A1700')
    
    // Product size
    ->setSize(750, TPG\Pcflib\Product::SIZE_MEASURE_ML)
    
    // The number of units that make up the product
    ->setUnits(6)

```

### Getting offers:

You can get an array of offers already added by using the `get()` method. The result will be an array of `Offer` objects.

```php
$offers = $feed->offers()->get();
```

### Altering offers:

You can change any value on the offer using the methods documented above at any time. If you need to modify one offer you can find its `Offer` instance by using the SKU number you set. Pass the number to the `find` method:

```php
$sku = 12;
$offer = $feed->offers()->find($sku);

$offer->setPrice(161.50);
```

The `find` method will return a singleton which means that any changes you make to the `Offer` instance will be updated on the feed automatically. If you don't want that to happen you can create a clone of the offer by calling the `clone` method:

```php
$offer = $feed->offers()->clone($sku);
```

This will give you a brand new `Offer` instance that is identical to the original, but completely separate.

### Deleting an offer

You can remove an offer from the feed by calling the `delete()` method on the `Offer` instance, or by passing the SKU number to the `delete()` method on the offer collection:

```php
$feed->offers()->delete($sku);

// or...

$offer = $feed->offers()->find($sku);
$offer->delete();
```

### Clearing the feed

You can clear the feed of all offers by calling the `purge` method:

```php
$feed->offers()->purge();
```

### Getting the number of offers

You can also keep an eye on the number of offers in the feed by calling the `count()` method:

```php
$count = $feed->offers()->count();
```

