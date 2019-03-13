<?php

namespace Tests;

use TPG\Pcflib\Builder;
use TPG\Pcflib\Categories\Book;
use TPG\Pcflib\Offer;

class BuilderTest extends TestCase
{
    /**
     * @test
     */
    public function the_builder_can_generate_an_array()
    {
        $feed = new Builder();
        $feed->offers()->add(
            (new Offer(['Books', 'Non-Fiction', 'Autobiographies'],
                (new Book())->author('Some Author')->isbn('1234-5678')->format(Book::FORMAT_HARDCOVER))
            )->name('Black and White: The Jake White Story')
            ->manufacturer('Zebra Books')
            ->price(249.99)
            ->sku(666555444)
        );

        $this->assertIsArray($feed->toArray()['Offers']);
        $this->assertEquals('Zebra Books', $feed->toArray()['Offers'][0]['Manufacturer']);
    }

    /**
     * @test
     */
    public function the_builder_can_generate_json()
    {
        $feed = new Builder();
        $feed->offers()->add(
            (new Offer(['Books', 'Non-Fiction', 'Autobiographies'],
                (new Book())->author('Some Author')->isbn('1234-5678')->format(Book::FORMAT_HARDCOVER))
            )->name('Black and White: The Jake White Story')
                ->manufacturer('Zebra Books')
                ->price(249.99)
                ->sku(666555444)
        );

        $this->assertStringContainsString('"Manufacturer":"Zebra Books"', $feed->toJson());
        $this->assertIsArray(json_decode($feed->toJson(), true));
    }

    /**
     * @test
     */
    public function the_builder_can_generate_xml()
    {
        $feed = new Builder();
        $feed->offers()->add(
            (new Offer(['Books', 'Non-Fiction', 'Autobiographies'],
                (new Book())->author('Some Author')->isbn('1234-5678')->format(Book::FORMAT_HARDCOVER))
            )->name('Black and White: The Jake White Story')
                ->manufacturer('Zebra Books')
                ->price(249.99)
                ->sku(666555444)
        );
        $feed->offers()->add(
            (new Offer(['Books', 'Fiction']))
            ->name('Another Product')
            ->manufacturer('Someone')
            ->price(169.95)
            ->sku(54321)
        );

        $xml = simplexml_load_string($feed->toXml());
        $this->assertEquals('Zebra Books', $xml->Offer[0]->Manufacturer);
        $this->assertEquals('Someone', $xml->Offer[1]->Manufacturer);
    }
}