<?php

declare(strict_types=1);

namespace GildedRose;

class ConjuredItem extends BasicItem
{
    protected function calculateQualityMultiplier(): int
    {
        return 2 * parent::calculateQualityMultiplier();
    }
}
