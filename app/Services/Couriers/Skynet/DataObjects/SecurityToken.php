<?php

namespace App\Services\Couriers\Skynet\DataObjects;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class SecurityToken extends Data
{
    public function __construct(
        #[MapInputName('SecurityToken')]
        public string $securityToken
    ) {}
}
