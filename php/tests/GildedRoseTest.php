<?php

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_not_have_negative_quality(): void
    {
        $items = [new Item('no_quality', 0, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertSame('no_quality', $items[0]->name);
        static::assertSame(0, $items[0]->quality);
    }

    /**
     * @test
     */
    public function it_should_not_have_more_than_50_quality(): void
    {
        $items = [new Item('Aged Brie', 0, 50)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertSame(50, $items[0]->quality);
    }

    /**
     * @test
     */
    public function it_should_up_quality_to_aged_brie(): void
    {
        $items = [new Item('Aged Brie', 1, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertSame(1, $items[0]->quality);
    }

    /**
     * @test
     */
    public function it_should_up_twice_quality_to_aged_brie_with_passed_sell_date(): void
    {
        $items = [new Item('Aged Brie', 0, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertSame(12, $items[0]->quality);
    }

    /**
     * @test
     */
    public function it_should_up_degrade_quality_for_normal_items(): void
    {
        $items = [new Item('normal_item', 5, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertSame(9, $items[0]->quality);
    }

    /**
     * @test
     */
    public function it_should_up_degrade_twice_quality_for_normal_items_with_passed_sell_date(): void
    {
        $items = [new Item('normal_item', 0, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertSame(8, $items[0]->quality);
    }

    /**
     * @test
     * @dataProvider provider_it_should_have_always_80_quality_for_Sulfuras_item
     */
    public function it_should_have_always_80_quality_for_Sulfuras_item(int $sellIn): void
    {
        $items = [new Item('Sulfuras, Hand of Ragnaros', $sellIn, 80)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertSame(80, $items[0]->quality, 'quality property should not be updated for Sulfuras item');
        static::assertSame($sellIn, $items[0]->sell_in, 'sellIn property should not be updated for Sulfuras item');
    }

    public function provider_it_should_have_always_80_quality_for_Sulfuras_item(): iterable
    {
        foreach ([-10, 0, 1, 10] as $sellIn) {
            yield [$sellIn];
        }
    }

    /**
     * @test
     * @dataProvider provider_it_should_up_quality_to_backstage_item
     */
    public function it_should_up_quality_to_backstage_item(int $sellIn, int $updatedQuality): void
    {
        $items = [new Item('Backstage passes to a TAFKAL80ETC concert', $sellIn, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertSame($updatedQuality, $items[0]->quality);
    }

    /**
     * Quality increases by 2 when there are 10 days or less and by 3 when there are 5 days or less but
     * Quality drops to 0 after the concert.
     */
    public function provider_it_should_up_quality_to_backstage_item(): iterable
    {
        yield [30, 11];
        yield [11, 11];
        for ($i = 10; $i >= 6; --$i) {
            yield [$i, 12];
        }
        for ($i = 5; $i >= 1; --$i) {
            yield [$i, 13];
        }
        yield [0, 0];
        yield [-1, 0];
    }
}
