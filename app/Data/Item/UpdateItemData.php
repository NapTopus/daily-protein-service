<?php

namespace App\Data\Item;

use App\Traits\ToUpdateArrayTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateItemData extends FormRequest
{
    use ToUpdateArrayTrait;

    public function __construct(
        public ?string $name = null,
        public ?float $protein = null,
    ) {
    }

    public static function fromRequest(array $reqeuestData): self
    {
        return new self(
            $reqeuestData['name'] ?? null,
            $reqeuestData['protein'] ?? null
        );
    }
}
