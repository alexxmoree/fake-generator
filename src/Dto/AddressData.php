<?php

namespace App\Dto;

class AddressData
{
    public function __construct(
        public string $city,
        public string $street,
        public string $building,
        public string $flat
    ) {}
}