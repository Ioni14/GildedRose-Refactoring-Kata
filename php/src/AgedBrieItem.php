<?php

declare(strict_types=1);

namespace GildedRose;

class AgedBrieItem extends UpdatingItem
{
    protected function updateSellin(): void
    {
        --$this->item->sell_in;
    }

    protected function updateQuality(): void
    {
        $this->item->quality += $this->calculateQualityMultiplier();
        $this->ensureQualityBounds();
    }

    private function calculateQualityMultiplier(): int
    {
        return $this->isSellDateHasExpired() ? 2 : 1;
    }
}
