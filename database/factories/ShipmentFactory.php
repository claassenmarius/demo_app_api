<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipment>
 */
class ShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::first();
        return [
            'user_id' => $user->id,
            'customer_reference' => fake()->uuid(),
            'receiver_name' => fake()->name(),
            'receiver_email' => fake()->email(),
            'receiver_mobile' => fake()->phoneNumber(),
            'sender_name' => fake()->name(),
            'sender_email' => fake()->email(),
            'sender_mobile' => fake()->phoneNumber(),
            'collection_address_street' => fake()->streetName(),
            'collection_address_suburb' => fake()->city(),
            'collection_address_city' => fake()->city(),
            'collection_address_postcode' => fake()->postcode(),
            'collection_address_province' => 'Western Cape',
            'collection_instructions' => fake()->sentence(),
            'delivery_address_street' => fake()->streetName(),
            'delivery_address_suburb' => fake()->city(),
            'delivery_address_city' => fake()->city(),
            'delivery_address_postcode' => fake()->postcode(),
            'delivery_address_province' => 'Western Cape',
            'delivery_instructions' => fake()->sentence(),
            'waybill_no' => fake()->randomNumber(5),
            'created_at' => now(),
            'updated_at' => now(),



        ];
    }
}
