<?php

namespace App\Traits;

trait ToArrayTrait
{
    public function toArray(): array
    {
        return array_filter(get_object_vars($this), fn ($value) => $value !== null);
    }
}
