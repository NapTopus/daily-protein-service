<?php

namespace App\Data;

class ItemData
{
    public function __construct(
        public string $name,
        public float $protein,
        public ?string $date = null
    ) {
    }
}
