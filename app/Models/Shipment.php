<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    protected $appends = [
        'collection_address',
        'delivery_address',
    ];

    public function user (): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parcels (): HasMany
    {
        return $this->hasMany(Parcel::class);
    }

    public function collectionAddress(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $collectionAddress = "{$attributes['collection_address_street']}, ";
                $collectionAddress .= "{$attributes['collection_address_suburb']}, ";
                $collectionAddress .= "{$attributes['collection_address_city']}, ";
                $collectionAddress .= "{$attributes['collection_address_postcode']}, ";
                $collectionAddress .= "{$attributes['collection_address_province']} ";

                return $collectionAddress;
            }
        );
    }

    public function deliveryAddress(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $deliveryAddress = "{$attributes['delivery_address_street']}, ";
                $deliveryAddress .= "{$attributes['delivery_address_suburb']}, ";
                $deliveryAddress .= "{$attributes['delivery_address_city']}, ";
                $deliveryAddress .= "{$attributes['delivery_address_postcode']}, ";
                $deliveryAddress .= "{$attributes['delivery_address_province']} ";

                return $deliveryAddress;
            }
        );
    }

}
