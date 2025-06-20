<?php

namespace App\Data\Item;

class StoreItemData
{
    public function __construct(
        public string $name,
        public float $protein,
        public ?string $date = null
    ) {
    }

    public static function fromRequest(array $reqeuestData): self
    {
        return new self(
            $reqeuestData['name'] ?? null,
            $reqeuestData['protein'] ?? null,
            $reqeuestData['date'] ?? null
        );
    }
}
