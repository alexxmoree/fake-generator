<?php

namespace App\Service\FakeData;

use App\Dto\CustomerData;
use Faker\Generator;

class OrderGenerator
{   
    private string $site;
    private string $orderType;
    private string $orderCountry;

    public function __construct(
        private Generator $faker,
        string $site,
        string $orderType,
        string $orderCountry
    ) {
        $this->site = $site;
        $this->orderType = $orderType;
        $this->orderCountry = $orderCountry;
    }

    public function createOrder(int $customerCrmId, CustomerData $customer): array
    {
        return [
            'customerId' => $customerCrmId,
            'firstName'  => $customer->firstName,
            'lastName'   => $customer->lastName,
            'email'      => $customer->email,
            'phone'      => $customer->phone,
            'countryIso' => $this->orderCountry,
            'orderType'  => $this->orderType,
            'site'       => $this->site,
            'status'     => $this->faker->randomElement(['new', 'complete', 'cancel-other']),
            'orderMethod'=> $this->faker->randomElement(['shopping-cart', 'phone', 'messenger']),
            'createdAt'  => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
            'items'      => $this->generateItems(),
        ];
    }

    private function generateItems(): array
    {
        $items = [];
        $count = $this->faker->numberBetween(1, 3);
        for ($i = 0; $i < $count; $i++) {
            $items[] = [
                'productName' => $this->faker->bothify('Товар ##??'),
                'quantity'    => $this->faker->numberBetween(1, 5),
                'price'       => (float) $this->faker->randomFloat(2, 100, 5000),
            ];
        }
        return $items;
    }
}