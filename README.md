# PCFLib
__Price Check Feed XML Library__

![](https://travis-ci.org/tpg/pcflib.svg?branch=master)

The PCFLib is an XML generator for the PriceCheck Offers XML Feed. The library is designed to help generate the XML needed so that PriceCheck can source product information over HTTP. The library 

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

This will also call the the `verifyAttributes` method on each `Offer` instance. If there's anything missing a `MissingRequiredAttribute` exception will be thrown.

## Adding Offers

You build up a Price Check feed by adding offers to the builder. The class provides a simple API for working with offers through the `offers()` method.

A single offer is represented by an instance of the `Offer` class. This class contains all the information for a single Price Check offer. You can create instances by adding instances of `Offer` and passing them to the `add()` method:

```php
// You can 
$feed->offers()->add((new TPG\Pcflib\Offer())->...);
```

You can add multiple offers by passing in an array:

```php
$feed->offers()->add([$offer1, $offer2]);
```

Or you can chain the `add` method multiple times:

```php
$feed->offers()->add($offer1)->add($offer2);
```

**## Counting the number of offers

You can also keep an eye on the number of offers in the feed by calling the `count()` method:

```php
$count = $feed->offers()->count();
```

### Counting the number of offers

You can also keep an eye on the number of offers in the feed by calling the `count()` method:

```php
$count = $feed->offers()->count();
```

### Setting the category

Price Check has a large catalog of products that are organized into categories. You can set the category by passing an array of category items to the `category()` method. The array MUST be flat and contain only the category names. The names must appear in the correct order. For example, if you're adding a book to the `Autobiographies` categry, you would use something like:

```php
$offer->category(['Books', 'Non-fiction', 'Autobiographies']);
```

It is important that the category names are correct. Check the website if you're unsure.

The `category()` method also takes a second argument which must be an instance of `Category` class. This is only required for categories that have additional attributes that need to be set. These categories are currently `Books`, `Videos`, `Clothing`, `Wine` and `Music`. Pcflib provides classes that extend `Category` for each of these:

```php
$offer->category(['Books', 'Non-fiction', 'Autobiographies'],
    (new TPC\Pcflib\Categories\Book)
        ->setFormat(TPC\Pcflib\Categories\Book::FORMAT_HARDCOVER)
        ->setIsbn('1234-5678-9012-3456')
        ->setAuthor('Job Rumble');
)
```

Note that PCFLib will NOT check if a `Category` instance has been provided if one is needed. This is something I might look at later, but for now it's on you to ensure you include the additional attributes if Price Check requires them.

PCFLib WILL, however, check if the right attributes have been included when using the `Category` classes.

For books, the Format, and ISBN numbers are required. For Videos, you must specify the Format, for Clothing you must specify the Age Group, Colour, Gender and Size of the item, and for Music, you must specify the Format. There are a bunch of optional attributes for each of those categories as well.

Missing a required attribute will throw a `MissingRequiredAttribute` exception.

### Setting product details

You'll also need to specify some more product specific details. You are required to provide at least a product name, manufacturer, description, SKU number and price.

```php
$offer->name('In Black and White: The Jake White Story')
    ->manufacturer('Zebra Press')
    ->description('In Black and White traces the life story of Springbok rugby coach Jake White, right up to and including the 2007 Rugby World Cup. [...] White's story will both absorb and astound.')
    ->sku(12)
    ->price(44.95);
)
```

There are a bunch of additional setters you can use as well:

```php
$offer->ean($ean)       // EAN barcode number
  ->upc($upc)           // UPC code
  ->modelNumber($model) // Model number
  ->productUrl($url)    // URL to a product on your website
  ->imageUrl($url)      // URL to an image of the product on yout website
  ->notes($notes)       // Notes about the product
  ->marketplace()       // Set product on the marketplace
  ->bundle()            // Set the product as a bundle
  ->groupId($group)     // Group ID for grouping individual products
;
```

The `marketplace()` and `bundle()` methods will automatically set those attributes to `true`, but can take a boolean parameter of `false` to turn those attributes off.

### Setting price details

All offers must have at least a price. However, you can also set a sale price and include delivery pricing as well. Note that Price Check require that the delivery price is for that product ALONE and without any additional products.

To set sale price and delivery cost, you can pass a second and third parameter to the `price()` method

```php
$offer->price(151.96, 139.50, 25);
/*
 * Set a price of R151.96
 * Set a sales price of R139.50
 * Set a delivery cost of R25.00
 */
```

If you need to change just the sales price or the delivery cost, you can use the appropriately named `salesPrice()` and `deliveryCost()` methods respectively.

### Contract pricing

Price Check also provides support for contract pricing. You can set this using the `contract()` method:

```php
$offer->contract(600, 24, TPG\Pcflib\Offer::CONTRACT_PERIOD_MONTHS);
```

The first parameter is the cash component, the second parameter is the period length and the last parameter is the period type which can be one of:

 - `CONTRACT_PERIOD_MONTHS`
 - `CONTRACT_PERIOD_WEEKS`
 - `CONTRACT_PERIOD_DAYS`

> Take note that the use of `contract` will NOT invalidate values set using the `price()`, `salesPrice()` or `deliveryPrice()` methods.

There are no separate methods for period length and period type and if you need to make changes to the contract pricing, you need to pass all three parameters to the `contract()` method.

### Setting product URLs

The links for the products can be set on the offer directly using the `productUrl` and `imageUrl` methods:

```php
$offer->productUrl('http://www.example.com/showproduct.php?product_id=21');
$offer->imageUrl('http://www.example.com/showimage.php?product_id=21');
```

### Marking an offer as Second Hand

Sometimes you may want to mark a product as "used" or "second hard". You can do so by calling the `secondHand()` method. If you need to _unset_ the used status of a product, pass a boolean "false" as the only parameter:

```php
$offer->secondHand();

// or...

$offer->secondHand(false);
```

In order to see a product as second hand, Price Check require that `SecondHand` appears in the optional `Notes` node. PCFLib will append `SecondHand` to the notes string only when exporting Array, JSON or XML data, so the use of the `notes()` setter will not remove it. Likewise, when changing the second hand status later on, any notes you may have set will not be affected.

### Setting stock availability

You can also pass in stock availability details. There is an `availability` method which takes two parameters. The first is either `Offer::AVAILABILITY_IN_STOCK` or `Offer::AVAILABILITY_OUT_OF_STOCK`. The second paramter is the stock level.

In addtion, if you need a product to display as _IN STOCK_ and ordered from a supplier, regardless of stock levels, you can pass `Offer::ORDERED_FROM_SUPPLIER` as the value of the second parameter to indicate an unlimited supply.

```php
$offer->availability(Offer::AVAILABILITY_IN_STOCK, 200);

$offer->availability(
  Offer::AVAILABILITY_IN_STOCK,
  Offer::ORDERED_FROM_SUPPLIER
);
```

### Setting Units and Sizes

Some products include multiples of the same item. An example could be a six-pack of soda, or a pack of envelopes. You can set the number of items and the size of each time using the `units()` method:

```php
/*
 * 6 units, of 750ml each
 */
$offer->units(6, 750, Offer::UNIT_MEASURE_ML);
```

There are a few other `UNIT_MEASURE` constants for `_MM`, `_CM`, `_M` and `_ML`, `_L` and `_KL`. Anything else you can simply enter a string of the measure you need.

---

## Editing Offers:

You can get an array of offers already added by using the `toArray()` method. The result will be an array of `Offer` objects.

```php
$offers = $feed->offers()->toArray();
```

PCFLib also provides a `toJson` method which will return a JSON string. Pass a boolean `true` as the only parameter and the string will be pretty printed.

### Finding and Altering offers

You can change any value on the offer using the methods documented above at any time. If you need to modify an offer you've already created, you can find its `Offer` instance by using the SKU number you set since SKU's will be unique. Pass the number to the `find` method:

```php
$sku = 12;
$offer = $feed->offers()->find($sku);

$offer->price(161.50);
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

### Clearing all offers

You can clear the feed of all offers by calling the `purge` method:

```php
$feed->offers()->purge();
```
