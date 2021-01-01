<?php

declare(strict_types=1);

namespace GildedRose;

class BackstageItem extends UpdatingItem
{
    protected function updateSellin(): void
    {
        --$this->item->sell_in;
    }

    protected function updateQuality(): void
    {
        if ($this->isSellDateHasExpired()) {
            $this->item->quality = 0;
            return;
        }
        $this->item->quality += $this->calculateQualityMultiplier();
        $this->ensureQualityBounds();
    }

    private function calculateQualityMultiplier(): int
    {
        $multiplier = 1;
        if ($this->item->sell_in < 5) {
            ++$multiplier;
        }
        if ($this->item->sell_in < 10) {
            ++$multiplier;
        }

        return $multiplier;
    }
}
