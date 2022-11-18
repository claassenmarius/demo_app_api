<?php

namespace App\Http\Controllers;

use App\Services\Couriers\Collections\ShipmentQuoteCollection;
use App\Services\Couriers\Contracts\CourierServiceContract;
use App\Services\Couriers\DataObjects\Shipment;

class ShipmentQuoteController extends Controller
{
    private array $couriers;

    public function __construct (CourierServiceContract ...$couriers)
    {
        $this->couriers = $couriers;
    }

    public function __invoke (Shipment $shipment): ShipmentQuoteCollection
    {
        $quotes = new ShipmentQuoteCollection();

        foreach ($this->couriers as $courier) {
            $quotes->add($courier->quote($shipment));
        }

        return $quotes->flatten();
    }
}
