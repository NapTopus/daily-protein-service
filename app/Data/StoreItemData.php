<?php

namespace App\Data;

class StoreItemData
{
    public function __construct(
        public string $name,
        public float $protein,
        public ?string $date = null
    ) {
    }
}
