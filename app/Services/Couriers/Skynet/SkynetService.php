<?php

namespace App\Services\Couriers\Skynet;

use App\Services\Couriers\Collections\ShipmentQuoteCollection;
use App\Services\Couriers\Contracts\CourierServiceContract;
use App\Services\Couriers\DataObjects\Shipment;
use App\Services\Couriers\DataObjects\ShipmentQuote;
use App\Services\Couriers\Skynet\DataObjects\SecurityToken;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SkynetService implements CourierServiceContract
{
    private string $serviceName = "Skynet";
    private array $serviceTypes = [
        "Overnight Express" => "ON1",
        "Budget Cargo" => "DBC"
    ];

    public function __construct(
        protected string $host,
        protected string $username,
        protected string $password,
        protected string|int $systemId,
        protected string $accountNumber,
    ) {}

    protected function request (): PendingRequest
    {
        return Http::acceptJson()
            ->contentType("application/json")
            ->retry(3, 3000)
            ->timeout(10);
    }

    protected function getSecurityToken (): SecurityToken
    {
        $response = $this->request()->post(url: "$this->host/Security/GetSecurityToken", data: [
            "Username" => $this->username,
            "Password" => $this->password,
            "SystemId" => $this->systemId,
            "AccountNumber" => $this->accountNumber,
        ]);

        if ($response->failed()) {
            // TODO: Create more specific exception
            throw new \Exception($response->reason(), $response->status());
        }

        if (!$response->json('SecurityToken')) {
            // TODO: Create more specific exception
            throw new \Exception("Unable to retrieve Skynet Security Token", 500);
        }

        return SecurityToken::from($response->json());
    }

    public function quote (Shipment $shipment): ?ShipmentQuoteCollection
    {
        $quoteCollection = new ShipmentQuoteCollection();

        foreach ($this->serviceTypes as $serviceDescription => $serviceCode) {
            $response = $this->request()->post("$this->host/Financial/GetQuote", [
                "SecurityToken" => $this->getSecurityToken()->securityToken,
                "AccountNumber" => $this->accountNumber,
                "FromCity" => $shipment->collectionAddressCity,
                "FromCityPostalCode" => $shipment->collectionAddressPostcode,
                "ToCity" => $shipment->deliveryAddressCity,
                "ToCityPostalCode" => $shipment->deliveryAddressPostcode,
                "ServiceType" => $serviceCode,
                "InsuranceType" => "1",
                "InsuranceAmount" => "",
                "DestinationPCode" => $shipment->deliveryAddressPostcode,
                "ParcelList" => [
                    [
                        "parcel_number" => "1",
                        "parcel_length" => $shipment->parcelLength,
                        "parcel_breadth" => $shipment->parcelWidth,
                        "parcel_height" => $shipment->parcelHeight,
                        "parcel_mass" => $shipment->parcelWeight,
                        "parcel_reference" => "$serviceCode - 77745",
                        "parcel_description" => "$serviceCode - 77745",
                    ]
                ],
            ]);

            if ($response->failed()) {
                // Rather than throwing an exception, continue to look for quotes for other service types
                continue;
                // throw new \Exception($response->reason(), $response->status());
            }

            $shipmentQuote = new ShipmentQuote(
                serviceName: $this->serviceName,
                serviceDescription: $serviceDescription,
                serviceCode: $serviceCode,
                rate: $response->json("charges")
            );

            $quoteCollection->add($shipmentQuote);
        }

        return $quoteCollection;
    }

}
