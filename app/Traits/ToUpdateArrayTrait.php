<?php

namespace App\Traits;

trait ToUpdateArrayTrait
{
    public function toUpdateArray(): array
    {
        return array_filter(get_object_vars($this), fn ($value) => $value !== null);
    }
}