<?php

namespace App\Data\Item;

use App\Traits\ToArrayTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateItemData extends FormRequest
{
    use ToArrayTrait;

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
