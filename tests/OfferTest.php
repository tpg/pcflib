<?php

namespace Tests;

use TPG\Pcflib\Builder;
use TPG\Pcflib\Categories\Book;
use TPG\Pcflib\Offer;
use TPG\Pcflib\OfferCollection;

class OfferTest extends TestCase
{
    /**
     * @test
     */
    public function a_feed_can_have_a_collection_of_offers()
    {
        $feed = new Builder();

        $offer = new Offer(['Books', 'Non-Fiction', 'Autobiographies']);

        $feed->offers()->add($offer);

        $this->assertCount(1, $feed->offers());
    }

    /**
     * @test
     */
    public function an_offer_can_have_extended_category_attributes()
    {
        $offer = new Offer(['Books', 'Non-Fiction', 'Autobiographies'], (new Book())->format(Book::FORMAT_HARDCOVER)->isbn('12345')->author('Author'));
        $this->assertArrayHasKey('Format', $offer->toArray()['Attributes']);
    }

    /**
     * @test
     */
    public function a_book_category_offer_will_return_delimited_authors()
    {
        $offer = new Offer(['Books', 'Non-Fiction', 'Autobiographies'], (new Book())
            ->format(Book::FORMAT_HARDCOVER)
            ->isbn('12345')
            ->author('Author One', 'Author Two', 'Author Three'));

        $this->assertEquals('Author One,Author Two,Author Three', $offer->toArray()['Attributes']['Author']);
    }

    /**
     * @test
     */
    public function an_offer_can_have_product_details()
    {
        $offer = new Offer(['Books', 'Non-Fiction', 'Autobiographies']);
        $offer->name('In Black and White: The Jake White Story')
            ->manufacturer('Zebra Press')
            ->sku(12)
            ->ean('600332531354341');

        $this->assertEquals(12, $offer->toArray()['ShopSKU']);
        $this->assertEquals('Zebra Press', $offer->toArray()['Manufacturer']);
    }

    /**
     * @test
     */
    public function an_offer_can_have_pricing_details()
    {
        $offer = new Offer(['Books', 'Non-Fiction', 'Autobiographies']);
        $offer->price(149.95, 139.95, 45);

        $this->assertEquals(149.95, $offer->toArray()['Price']);
        $this->assertEquals(139.95, $offer->toArray()['SalePrice']);
    }

    /**
     * @test
     */
    public function an_offer_can_have_contract_pricing()
    {
        $offer = new Offer(['Books', 'Non-Fiction', 'Autobiographies']);
        $offer->contractPricing(600, 24, Offer::CONTRACT_PERIOD_MONTHS);

        $this->assertEquals(600, $offer->toArray()['ContractPricing']['CashComponent']);
        $this->assertEquals('Months', $offer->toArray()['ContractPricing']['PeriodType']);
    }

    /**
     * @test
     */
    public function an_offer_can_have_product_urls()
    {
        $offer = new Offer(['Books', 'Non-Fiction', 'Autobiographies']);
        $offer->productUrl('https://product.test')->imageUrl('https://image.test');

        $this->assertEquals('https://product.test', $offer->toArray()['ProductURL']);
        $this->assertEquals('https://image.test', $offer->toArray()['ImageURL']);
    }

    /**
     * @test
     */
    public function an_offer_can_be_marked_as_second_hand()
    {
        $offer = new Offer(['Books', 'Non-Fiction', 'Autobiographies']);
        $offer->secondHand()->notes('This product is not new');

        $this->assertStringContainsString('SecondHand', $offer->toArray()['Notes']);
    }

    /**
     * @test
     */
    public function offers_can_be_found_by_their_sku_and_altered()
    {
        $feed = new Builder();
        $feed->offers()->add((new Offer(
            ['Books', 'Non-Fiction', 'Autobiographies'],
                (new Book())->isbn('54321')->format(Book::FORMAT_HARDCOVER)->author('Joe Schmoe')
            )
        )->sku(12345));

        $offer = $feed->offers()->find(12345);

        $this->assertEquals('Books > Non-Fiction > Autobiographies', $offer->toArray()['Category']);

        $offer->category(['Books', 'Children', 'Boys']);

        $this->assertEquals('Books > Children > Boys', $offer->toArray()['Category']);
        $this->assertEquals('Joe Schmoe', $offer->toArray()['Attributes']['Author']);

        $this->assertEquals('Books > Children > Boys', $feed->offers()->find(12345)->toArray()['Category']);
    }

    /**
     * @test
     */
    public function offers_can_be_cloned_by_their_sku()
    {
        $feed = new Builder();
        $feed->offers()->add(
            (new Offer(['Books', 'Non-Fiction', 'Autobiographies']))->sku(1234567890)
        );

        $clone = $feed->offers()->clone(1234567890);

        $clone->sku('0987654321');
        $clone->category(['Computers', 'Apple']);

        $this->assertNull($feed->offers()->find('0987654321'));

        $this->assertNotEquals('Computers > Apple', $feed->offers()->find(1234567890)->toArray()['Category']);
    }

    /**
     * @test
     */
    public function an_offer_can_be_deleted_by_sku()
    {
        $feed = new Builder();
        $offer = new Offer(['Books', 'Non-Fiction', 'Autobiographies']);
        $offer->sku(10);
        $offer2 = new Offer(['Clothing', 'Children', 'Girls']);
        $offer2->sku(20);

        $feed->offers()->add([$offer, $offer2]);

        $feed->offers()->delete(10);

        $this->assertCount(1, $feed->offers());
        $this->assertEquals(20, $feed->offers()[0]->toArray()['ShopSKU']);
    }

    /**
     * @test
     */
    public function an_offer_can_delete_itself_from_the_collection()
    {
        $feed = new Builder();
        $offer = new Offer(['Books', 'Non-Fiction', 'Autobiographies']);
        $offer->sku(10);
        $offer2 = new Offer(['Clothing', 'Children', 'Girls']);
        $offer2->sku(20);

        $feed->offers()->add([$offer, $offer2]);

        $feed->offers()->find(10)->delete();

        $this->assertCount(1, $feed->offers());
        $this->assertEquals(20, $feed->offers()[0]->toArray()['ShopSKU']);
    }

    /**
     * @test
     */
    public function a_collection_can_be_purged()
    {
        $feed = new Builder();
        $offer = new Offer(['Books', 'Non-Fiction', 'Autobiographies']);
        $offer->sku(10);
        $offer2 = new Offer(['Clothing', 'Children', 'Girls']);
        $offer2->sku(20);

        $feed->offers()->add([$offer, $offer2]);

        $feed->offers()->purge();

        $this->assertCount(0, $feed->offers());
    }
}