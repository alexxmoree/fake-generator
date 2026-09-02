<?php

namespace App\Service\FakeData;

use App\Entity\Order;
use App\Entity\Customer;
use Faker\Generator;

class OrderGenerator
{
    private Generator $faker;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    public function createOrder(Customer $customer): Order
    {
        $order = new Order;
        $order->setCustomer($customer);
        $order->setStatus($this->faker->randomElement(['complete', 'new', 'cancel-other']));
        $order->setTotalAmount((string) $this->faker->randomFloat(2, 1000, 5000));
        $order->setCreatedAt(\DateTimeImmutable::createFromMutable(
            $this->faker->dateTimeBetween('-1 year', 'now')
        ));

        return $order;
    }
}