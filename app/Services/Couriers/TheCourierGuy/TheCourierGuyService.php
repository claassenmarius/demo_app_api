<?php

namespace App\Services\Couriers\TheCourierGuy;

use App\Services\Couriers\Collections\ShipmentQuoteCollection;
use App\Services\Couriers\Contracts\CourierServiceContract;
use App\Services\Couriers\DataObjects\Shipment;
use App\Services\Couriers\DataObjects\ShipmentQuote;
use Aws\Credentials\Credentials;
use Aws\Signature\SignatureV4;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;

class TheCourierGuyService implements CourierServiceContract
{
    private string $serviceName = "The Courier Guy";

    public function __construct(
        protected string $host,
        protected string $accessId,
        protected string $accessSecret,
    ){}

    private function request ()
    {
        return Http::withMiddleware(
            Middleware::mapRequest(function (RequestInterface $request) {
                $signature = new SignatureV4('execute-api', 'af-south-1');
                $credentials = new Credentials($this->accessId, $this->accessSecret);
                return $signature->signRequest($request, $credentials);
            })
        )
            ->acceptJson()
            ->contentType("application/json")
            ->retry(3, 3000)
            ->timeout(10);
    }

    public function quote(Shipment $shipment): ?ShipmentQuoteCollection
    {
        $quoteCollection = new ShipmentQuoteCollection();

        $response = $this->request()->post("$this->host/rates", [
            "collection_address" => [
                "street_address" => $shipment->collectionAddressStreet,
                "local_area" => $shipment->collectionAddressSuburb,
                "city" => $shipment->collectionAddressCity,
                "code" => $shipment->collectionAddressPostcode,
                "zone" => $shipment->collectionAddressProvince,
                "country" => "ZA"
            ],
            "delivery_address" => [
                "street_address" => $shipment->deliveryAddressStreet,
                "local_area" => $shipment->deliveryAddressSuburb,
                "city" => $shipment->deliveryAddressCity,
                "code" => $shipment->deliveryAddressPostcode,
                "zone" => $shipment->deliveryAddressProvince,
                "country" => "ZA"
            ],
            "parcels" => [
                [
                    "submitted_length_cm" => $shipment->parcelLength,
                    "submitted_width_cm" => $shipment->parcelWidth,
                    "submitted_height_cm" => $shipment->parcelHeight,
                    "submitted_weight_kg" => $shipment->parcelWeight,
                ]
            ],
        ]);

        if ($response->failed()) {
            // Do not do anything if the response failed
            return null;
            // throw new \Exception($response->reason(), $response->status());
        }

        foreach ($response->collect('rates') as $serviceRate) {
            $shipmentQuote = new ShipmentQuote(
                serviceName: $this->serviceName,
                serviceDescription: $serviceRate["service_level"]["name"],
                serviceCode: $serviceRate["service_level"]["code"],
                rate: $serviceRate['rate'],
            );

            $quoteCollection->add($shipmentQuote);
        }

        ray($quoteCollection);
        return $quoteCollection;
    }
}
