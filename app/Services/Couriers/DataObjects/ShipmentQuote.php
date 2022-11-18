<?php

namespace App\Services\Couriers\DataObjects;

use Spatie\LaravelData\Data;

class ShipmentQuote extends Data
{
    public function __construct(
        public string $serviceName,
        public string $serviceDescription,
        public string $serviceCode,
        public string $rate,
    ) {}
}
