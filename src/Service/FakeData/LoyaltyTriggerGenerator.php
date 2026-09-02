<?php

namespace App\Service\FakeData;

use App\Entity\Order;
use App\Entity\LoyaltyTrigger;
use App\Enum\LoyaltyTriggerType;
use Faker\Generator;

class LoyaltyTriggerGenerator
{
    private Generator $faker;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    public function createLoyaltyTrigger(Order $order): LoyaltyTrigger
    {
        $loyaltyTrigger = new LoyaltyTrigger;
        $loyaltyTrigger->setType(LoyaltyTriggerType::CreditForOrder);
        $loyaltyTrigger->setRelatedOrder($order);
        $loyaltyTrigger->setPointsEarned($this->faker->numberBetween(100, 500));
        $loyaltyTrigger->setCreatedAt($order->getCreatedAt());

        return $loyaltyTrigger;
    }
}