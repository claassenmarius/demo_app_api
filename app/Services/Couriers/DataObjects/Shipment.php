<?php

namespace App\Services\Couriers\DataObjects;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

class Shipment extends Data
{
    public function __construct(
        public string $collectionAddressStreet,
        public string $collectionAddressSuburb,
        public string $collectionAddressCity,
        public string $collectionAddressProvince,
        public string $collectionAddressPostcode,

        public string $deliveryAddressStreet,
        public string $deliveryAddressSuburb,
        public string $deliveryAddressCity,
        public string $deliveryAddressProvince,
        public string $deliveryAddressPostcode,

        public float $parcelLength,
        public float $parcelWidth,
        public float $parcelHeight,
        public float $parcelWeight,
    ) {}
}
