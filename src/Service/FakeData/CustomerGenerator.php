<?php

namespace App\Service\FakeData;

use Faker\Generator;
use App\Dto\CustomerData;
use App\Dto\AddressData;

class CustomerGenerator
{
    private Generator $faker;
    private string $site;

    public function __construct(Generator $faker, string $site)
    {
        $this->faker = $faker;
        $this->site = $site;
    }

    public function createCustomer(): CustomerData
    {
        $address = new AddressData(
            city: $this->faker->city(),
            street: $this->faker->streetAddress(),
            building: $this->faker->buildingNumber(),
            flat: (string) $this->faker->numberBetween(1, 200)
        );

        return new CustomerData(
            firstName: $this->faker->firstName(),
            lastName: $this->faker->lastName(),
            email: $this->faker->email(),
            phone: $this->faker->phoneNumber(),
            birthday: \DateTimeImmutable::createFromMutable(
                $this->faker->dateTimeBetween('-50 years', '-10 years')
            ),
            address: $address,
            site: $this->site
        );
    }
}