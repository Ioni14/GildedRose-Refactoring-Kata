<?php

declare(strict_types=1);

namespace GildedRose;

abstract class UpdatingItem
{
    protected $item;

    protected function __construct(Item $item)
    {
        $this->item = $item;
    }

    final public static function createFromItem(Item $item): self
    {
        switch ($item->name) {
            case 'Backstage passes to a TAFKAL80ETC concert':
                return new BackstageItem($item);
            case 'Aged Brie':
                return new AgedBrieItem($item);
            case 'Sulfuras, Hand of Ragnaros':
                return new LegendaryItem($item);
        }

        return new BasicItem($item);
    }

    final public function update(): void
    {
        $this->updateSellin();
        $this->updateQuality();
    }

    protected function isSellDateHasExpired(): bool
    {
        return $this->item->sell_in < 0;
    }

    protected function ensureQualityBounds(): void
    {
        $this->item->quality = max(0, min(50, $this->item->quality));
    }

    abstract protected function updateSellin(): void;

    abstract protected function updateQuality(): void;
}
