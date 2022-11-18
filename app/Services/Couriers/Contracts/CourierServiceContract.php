<?php

namespace App\Services\Couriers\Contracts;

use App\Services\Couriers\Collections\ShipmentQuoteCollection;
use App\Services\Couriers\DataObjects\Shipment;

interface CourierServiceContract
{
    public function quote (Shipment $shipment): ?ShipmentQuoteCollection;
}
