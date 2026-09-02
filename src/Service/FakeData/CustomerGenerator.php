<?php

namespace App\Service\FakeData;

use App\Entity\Customer;
use Faker\Generator;

class CustomerGenerator
{
    private Generator $faker;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    public function createCustomer(): Customer
    {
        $customer = new Customer;
        $customer->setFirstName($this->faker->firstName());
        $customer->setLastName($this->faker->lastName());
        $customer->setPhone($this->faker->unique()->phoneNumber);
        $customer->setEmail($this->faker->unique()->email);

        return $customer;
    }
}