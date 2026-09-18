<?php

namespace App\Dto;

class CustomerData
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $phone,
        public \DateTimeInterface $birthday,
        public AddressData $address,
        public string $site
    ) {}
}